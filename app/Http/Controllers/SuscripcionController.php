<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use App\Models\Usuario;
use App\Models\Gimnasio;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controlador de Suscripciones
 *
 * Gestiona el ciclo de vida de las suscripciones de los usuarios a planes.
 *
 * Reglas de negocio implementadas:
 * - Roles:
 *   - Administrador: puede ver/gestionar suscripciones. Si hay un "gimnasio_activo" en sesión, se filtra por él.
 *   - Monitor: solo puede ver/crear/modificar suscripciones de su propio gimnasio.
 * - Seguridad:
 *   - Un monitor nunca puede operar sobre suscripciones de otro gimnasio (403).
 * - Integridad:
 *   - La suscripción siempre queda asociada al gimnasio del usuario (id_gimnasio).
 *   - En edición NO se permite cambiar el usuario de una suscripción (id_usuario no cambia).
 * - Precio:
 *   - El precio NO se edita manualmente.
 *   - Al crear o cambiar el plan, el precio histórico (suscripciones.precio) se copia desde el precio actual del plan (planes.precio).
 * - Fechas:
 *   - fecha_alta no se modifica en update (se mantiene la original).
 *   - Si activa = true => fecha_baja = null
 *   - Si activa = false y no hay fecha_baja => se establece hoy.
 */
class SuscripcionController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     * * Establece el middleware de autenticación y restringe el acceso a
     * usuarios con roles de Administrador o Monitor.
     */
    public function __construct()
    {
        // Admin y Monitor
        $this->middleware(['auth','rol:Administrador,Monitor']);
    }

    /**
     * Muestra el listado de suscripciones.
     *
     * - Administrador:
     *   - Si existe session('gimnasio_activo'), filtra por ese gimnasio.
     *   - Si no existe, muestra todas.
     * - Monitor:
     *   - Solo muestra las de su propio gimnasio.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $empleado = auth('web')->user();

        $query = Suscripcion::with(['usuario', 'gimnasio', 'plan'])
            ->orderByDesc('id_suscripcion');

        if ($empleado->rol === 'Administrador' && session('gimnasio_activo')) {
            $query->where('id_gimnasio', (int) session('gimnasio_activo'));
        } elseif ($empleado->rol !== 'Administrador') {
            $query->where('id_gimnasio', (int) $empleado->id_gimnasio);
        }

        $suscripciones = $query->paginate(10);

        return view('suscripciones.index', compact('suscripciones'));
    }


     /**
     * Comprueba que el empleado actual puede operar sobre una suscripción.
     *
     * - Administrador: permitido.
     * - Monitor: solo permitido si la suscripción pertenece a su gimnasio.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return void
     */
    private function assertMismoGimnasio(Suscripcion $suscripcion): void
    {
        $empleado = auth('web')->user();

        if ($empleado->rol === 'Administrador') {
            return;
        }

        if ((int) $suscripcion->id_gimnasio !== (int) $empleado->id_gimnasio) {
            abort(403);
        }
    }

    /**
     * Devuelve el gimnasio "en contexto" según el rol:
     *
     * - Administrador: session('gimnasio_activo') si existe, si no, null.
     * - Monitor: su propio id_gimnasio.
     *
     * @return int|null
     */
    private function gimnasioEnContexto(): ?int
    {
        $empleado = auth('web')->user();

        if ($empleado->rol === 'Administrador') {
            return session('gimnasio_activo') ? (int) session('gimnasio_activo') : null;
        }

        return (int) $empleado->id_gimnasio;
    }

    /**
     * Obtiene el precio actual del plan desde la base de datos.
     *
     * Si el plan no existe o está inactivo, lanza una ValidationException
     * para que el error aparezca como error de formulario.
     *
     * @param  int  $idPlan
     * @return float
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function precioActualDePlan(int $idPlan): float
    {
        $plan = Plan::where('id_plan', $idPlan)
            ->where('activo', 1)
            ->first();

        if (!$plan) {
            throw ValidationException::withMessages([
                'id_plan' => 'El plan seleccionado no existe o está inactivo.',
            ]);
        }

        return (float) $plan->precio;
    }

    /**
     * Muestra el formulario para crear una nueva suscripción.
     *
     * - Filtra usuarios por gimnasio en contexto (si existe).
     * - Lista planes activos.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $idGimnasio = $this->gimnasioEnContexto();

        $usuariosQuery = Usuario::where('activo', 1)->orderBy('nombre');

        if ($idGimnasio) {
            $usuariosQuery->where('id_gimnasio', $idGimnasio);
        }

        $usuarios = $usuariosQuery->get();

        $planes = Plan::where('activo', 1)->orderBy('nombre')->get();

        return view('suscripciones.create', compact('usuarios', 'planes', 'idGimnasio'));
    }

    /**
     * Almacena una nueva suscripción.
     *
     * Reglas:
     * - El usuario seleccionado debe pertenecer al gimnasio en contexto si existe (admin con gimnasio_activo o monitor).
     * - id_gimnasio se fuerza desde el usuario.
     * - precio se copia desde el plan (no viene del formulario).
     * - activa = 1 y fecha_baja = null
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $r)
    {
        $empleado = auth('web')->user();

        $data = $r->validate([
            'id_usuario' => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'id_plan'    => ['required', 'integer', 'exists:planes,id_plan'],
            'fecha_alta' => ['required', 'date'],
        ]);

        $idGimnasioPermitido = $this->gimnasioEnContexto();

        $usuario = Usuario::findOrFail($data['id_usuario']);

        // Si hay gimnasio en contexto, el usuario debe ser de ese gimnasio
        if ($idGimnasioPermitido && (int) $usuario->id_gimnasio !== (int) $idGimnasioPermitido) {
            abort(403);
        }

        $plan = Plan::where('activo', 1)->findOrFail($data['id_plan']);

        $payload = [
            'id_usuario'  => (int) $usuario->id_usuario,
            'id_gimnasio' => (int) $usuario->id_gimnasio, // SIEMPRE del usuario
            'id_plan'     => (int) $plan->id_plan,
            'precio'      => (float) $plan->precio, // copia precio actual del plan
            'fecha_alta'  => $data['fecha_alta'],
            'fecha_baja'  => null,
            'activa'      => 1,
        ];

        Suscripcion::create($payload);
        return redirect()->route('suscripciones.index')->with('ok', 'Suscripción creada');
    }

    /**
     * Muestra formulario de edición.
     *
     * Reglas:
     * - NO se permite cambiar el usuario (solo mostramos info y ocultamos id_usuario en la vista).
     * - Permitimos cambiar plan y estado (activa/fecha_baja).
     *
     * @param  Suscripcion  $suscripcione
     * @return View
    */
    public function edit(Suscripcion $suscripcione): View
    {
        $suscripcion = $suscripcione;

        $this->assertMismoGimnasio($suscripcion);

        $usuario = $suscripcion->usuario; // usuario actual (no seleccionable)

        $planes = Plan::where('activo', 1)
            ->orWhere('id_plan', $suscripcion->id_plan) // por si el plan quedó inactivo pero está en uso
            ->orderBy('nombre')
            ->get();

        // Para admin, si quieres mostrar info del gimnasio en el formulario (opcional)
        $empleado = auth('web')->user();
        $gimnasio = null;

        if ($empleado->rol === 'Administrador') {
            $gimnasio = Gimnasio::find($suscripcion->id_gimnasio);
        }

        return view('suscripciones.edit', compact('suscripcion', 'usuario', 'planes', 'gimnasio'));
    }


    /**
     * Actualiza una suscripción.
     *
     * Reglas:
     * - No se puede cambiar id_usuario ni id_gimnasio.
     * - No se modifica fecha_alta (se mantiene).
     * - El precio se recalcula automáticamente si cambia el plan (y también lo fijamos siempre a partir del plan).
     * - Si activa = 1 => fecha_baja = null
     * - Si activa = 0 y fecha_baja vacía => fecha_baja = hoy
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \App\Models\Suscripcion   $suscripcione
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $r, Suscripcion $suscripcione)
    {
        $suscripcion = $suscripcione;

        $this->assertMismoGimnasio($suscripcion);

        $data = $r->validate([
            'id_plan'    => ['required', 'integer', 'exists:planes,id_plan'],
            'fecha_baja' => ['nullable', 'date'],
            'activa'     => ['required', 'boolean'],
        ]);

        // Forzar invariantes (no editables)
        $data['id_usuario']  = (int) $suscripcion->id_usuario;
        $data['id_gimnasio'] = (int) $suscripcion->id_gimnasio;

        // Si cambia el plan => recalcular precio al precio actual del plan
        if ((int) $data['id_plan'] !== (int) $suscripcion->id_plan) {
            $plan = Plan::findOrFail((int) $data['id_plan']);
            $data['precio'] = (float) $plan->precio;
        } else {
            // Si no cambia el plan, no tocamos el precio histórico
            unset($data['precio']);
        }

        // Reglas activa / fecha_baja
        if ((bool) $data['activa'] === true) {
            $data['fecha_baja'] = null;
        } else {
            if (empty($data['fecha_baja'])) {
                $data['fecha_baja'] = now()->toDateString();
            }
        }

        // No tocar fecha_alta: NO se incluye en $data
        $suscripcion->update($data);

        return redirect()->route('suscripciones.index')->with('ok', 'Suscripción actualizada');
    }

    /**
     * Elimina una suscripción.
     *
     * Nota: si en tu interfaz ya solo muestras el botón a Administrador,
     * igualmente es recomendable protegerlo también aquí con rol:Administrador.
     *
     * Si prefieres dejarlo tal cual y solo usarlo para admin, puedes:
     * - o añadir un middleware en rutas para destroy
     * - o comprobar rol aquí (ver comentario).
     *
     * @param  \App\Models\Suscripcion  $suscripcione
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Suscripcion $suscripcione)
    {
        // Recomendación extra de seguridad: solo admin
        // if (auth('web')->user()->rol !== 'Administrador') abort(403);

        $this->assertMismoGimnasio($suscripcione);

        $suscripcione->delete();

        return redirect()->route('suscripciones.index')->with('ok', 'Suscripción eliminada correctamente.');
    }
}