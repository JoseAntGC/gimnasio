<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controlador de Asignaciones.
 *
 * Gestiona la asignación de servicios (clases/actividades) a empleados
 * (monitores/administradores) en un día y hora concretos.
 *
 * Reglas de acceso:
 * - Administrador:
 *   - Puede ver el listado (index).
 *   - Puede crear/editar/eliminar asignaciones.
 *   - Si existe session('gimnasio_activo'), se filtra por ese gimnasio.
 * - Monitor:
 *   - Solo puede ver el listado (index).
 *   - Solo ve asignaciones del gimnasio al que pertenece.
 *
 * Reglas de negocio:
 * - Empleado y servicio deben pertenecer al MISMO gimnasio.
 * - Un empleado NO puede tener dos asignaciones el mismo día y a la misma hora,
 *   aunque sea para servicios distintos.
 */
class AsignacionController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     *
     * Capas de seguridad:
     * - Lectura (index): Administrador y Monitor.
     * - Gestión (create/store/edit/update/destroy): SOLO Administrador.
     */
    public function __construct()
    {
        // Ver listado: Admin y Monitor
        $this->middleware(['auth', 'rol:Administrador,Monitor'])->only(['index']);

        // Gestionar: SOLO Admin
        $this->middleware(['auth', 'rol:Administrador'])->except(['index']);
    }

    /**
     * Obtiene el gimnasio "en contexto" para filtrar datos.
     *
     * - Administrador: usa session('gimnasio_activo') si existe; si no, null (sin filtro).
     * - Monitor: siempre su id_gimnasio.
     *
     * @return int|null ID de gimnasio en contexto o null si no hay filtro.
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
     * Muestra el listado paginado de asignaciones.
     *
     * Carga relaciones:
     * - empleado
     * - servicio
     *
     * Filtro por gimnasio:
     * - Administrador: si hay session('gimnasio_activo'), filtra por ese gimnasio.
     * - Monitor: siempre filtra por su gimnasio.
     * -filtro por día: si se pasa 'dia' en query string.
     *
     * @return View
     */
    public function index(): View
    {
        $empleado = auth('web')->user();

        $query = Asignacion::with(['empleado','servicio'])
            ->orderBy('dia')
            ->orderBy('hora');

        // Filtro por gimnasio
        if ($empleado->rol === 'Administrador' && session('gimnasio_activo')) {
            $idGim = (int) session('gimnasio_activo');

            $query->whereHas('empleado', function ($q) use ($idGim) {
                $q->where('id_gimnasio', $idGim);
            });
        }

        if ($empleado->rol === 'Monitor') {
            $idGim = (int) $empleado->id_gimnasio;

            $query->whereHas('empleado', function ($q) use ($idGim) {
                $q->where('id_gimnasio', $idGim);
            });
        }

        // 👉 NUEVO: filtro por día
        if (request('dia')) {
            $query->where('dia', request('dia'));
        }

        $asignaciones = $query->paginate(12)->withQueryString();

        $dias = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];

        return view('asignaciones.index', compact('asignaciones','dias'));
    }


    /**
     * Muestra el formulario de creación de una nueva asignación.
     *
     * Filtra empleados/servicios por gimnasio en contexto (si existe).
     * - Si el Administrador tiene session('gimnasio_activo'), solo aparecen empleados y servicios de ese gimnasio.
     * - Si NO hay gimnasio activo (Admin), aparecerán de todos los gimnasios.
     *
     * @return View
     */
    public function create(): View
    {
        $idGim = $this->gimnasioEnContexto(); // para admin puede ser null

        $empleadosQuery = Empleado::where('activo', 1)
            ->whereIn('rol', ['Administrador', 'Monitor'])
            ->orderBy('apellidos')
            ->orderBy('nombre');

        $serviciosQuery = Servicio::where('activo', 1)
            ->orderBy('nombre');

        if ($idGim) {
            $empleadosQuery->where('id_gimnasio', (int) $idGim);
            $serviciosQuery->where('id_gimnasio', (int) $idGim);
        }

        $empleados = $empleadosQuery->get();
        $servicios = $serviciosQuery->get();

        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

        return view('asignaciones.create', compact('empleados', 'servicios', 'dias', 'idGim'));
    }

    /**
     * Guarda una nueva asignación.
     *
     * Reglas:
     * - Empleado y servicio deben pertenecer al mismo gimnasio.
     * - Si existe gimnasio en contexto (session gimnasio_activo), ambos deben ser de ese gimnasio.
     * - Un empleado no puede tener 2 asignaciones en el mismo día y hora (aunque sea otro servicio).
     *
     * @param  Request  $r
     * @return RedirectResponse
     * @throws ValidationException Si no cumple reglas de negocio (mensajes de validación).
     */
    public function store(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'id_empleado' => ['required', 'integer', 'exists:empleado,id_empleado'],
            'id_servicio' => ['required', 'integer', 'exists:servicio,id_servicio'],
            'dia'         => ['required', Rule::in(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'])],
            'hora'        => ['required', 'date_format:H:i'],
        ]);

        $idGim = $this->gimnasioEnContexto(); // Admin: session o null

        $emp = Empleado::findOrFail($data['id_empleado']);
        $srv = Servicio::findOrFail($data['id_servicio']);

        // Regla: empleado y servicio deben ser del mismo gimnasio
        if ((int) $emp->id_gimnasio !== (int) $srv->id_gimnasio) {
            throw ValidationException::withMessages([
                'id_servicio' => 'El servicio debe pertenecer al mismo gimnasio que el empleado.',
            ]);
        }

        // Si hay gimnasio en contexto, obligar a que ambos pertenezcan a ese gimnasio
        if ($idGim) {
            $idGim = (int) $idGim;

            if ((int) $emp->id_gimnasio !== $idGim || (int) $srv->id_gimnasio !== $idGim) {
                abort(403);
            }
        }

        // Regla clave: un empleado NO puede tener dos asignaciones mismo día y hora
        $dupSlot = Asignacion::where('id_empleado', $data['id_empleado'])
            ->where('dia', $data['dia'])
            ->where('hora', $data['hora'])
            ->exists();

        if ($dupSlot) {
            throw ValidationException::withMessages([
                'hora' => 'Este empleado ya tiene una asignación ese día a esa hora.',
            ]);
        }

        Asignacion::create($data);

        return redirect()->route('asignaciones.index')->with('ok', 'Asignación creada');
    }

    /**
     * Muestra el formulario de edición de una asignación existente.
     *
     * - Filtra empleados/servicios por gimnasio en contexto (si existe).
     * - Si el Admin tiene gimnasio activo, no debería editar asignaciones de otro gimnasio.
     *
     * @param  Asignacion  $asignacione  Asignación (Route Model Binding).
     * @return View
     */
    public function edit(Asignacion $asignacione): View
    {
        $asignacion = $asignacione;

        $idGim = $this->gimnasioEnContexto();

        // Si admin tiene gimnasio activo, impedir editar asignaciones fuera de ese gimnasio
        if ($idGim) {
            $idGim = (int) $idGim;

            // nos apoyamos en la relación empleado
            if ($asignacion->relationLoaded('empleado') === false) {
                $asignacion->load('empleado');
            }

            if ((int) $asignacion->empleado->id_gimnasio !== $idGim) {
                abort(403);
            }
        }

        $empleados = Empleado::where('activo', 1)
            ->whereIn('rol', ['Administrador', 'Monitor'])
            ->when($idGim, fn ($q) => $q->where('id_gimnasio', (int) $idGim))
            ->orderBy('apellidos')
            ->orderBy('nombre')
            ->get();

        $servicios = Servicio::where('activo', 1)
            ->when($idGim, fn ($q) => $q->where('id_gimnasio', (int) $idGim))
            ->orderBy('nombre')
            ->get();

        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

        return view('asignaciones.edit', compact('asignacion', 'empleados', 'servicios', 'dias', 'idGim'));
    }

    /**
     * Actualiza una asignación.
     *
     * Reglas:
     * - Empleado y servicio deben ser del mismo gimnasio.
     * - Si hay gimnasio en contexto, ambos deben pertenecer a ese gimnasio.
     * - No se permite que el empleado tenga otra asignación en el mismo día/hora
     *   (excluyendo la asignación actual).
     *
     * @param  Request    $r
     * @param  Asignacion $asignacione
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $r, Asignacion $asignacione): RedirectResponse
    {
        $asignacion = $asignacione;

        $data = $r->validate([
            'id_empleado' => ['required', 'integer', 'exists:empleado,id_empleado'],
            'id_servicio' => ['required', 'integer', 'exists:servicio,id_servicio'],
            'dia'         => ['required', Rule::in(['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'])],
            'hora'        => ['required', 'date_format:H:i'],
        ]);

        $idGim = $this->gimnasioEnContexto();

        $emp = Empleado::findOrFail($data['id_empleado']);
        $srv = Servicio::findOrFail($data['id_servicio']);

        // Regla: empleado y servicio del mismo gimnasio
        if ((int) $emp->id_gimnasio !== (int) $srv->id_gimnasio) {
            throw ValidationException::withMessages([
                'id_servicio' => 'El servicio debe pertenecer al mismo gimnasio que el empleado.',
            ]);
        }

        // Si hay gimnasio en contexto, obligar a que ambos pertenezcan a ese gimnasio
        if ($idGim) {
            $idGim = (int) $idGim;

            if ((int) $emp->id_gimnasio !== $idGim || (int) $srv->id_gimnasio !== $idGim) {
                abort(403);
            }
        }

        // Regla clave: evitar doble asignación del mismo empleado mismo día/hora
        $dupSlot = Asignacion::where('id_empleado', $data['id_empleado'])
            ->where('dia', $data['dia'])
            ->where('hora', $data['hora'])
            ->where('id_asignacion', '<>', $asignacion->id_asignacion)
            ->exists();

        if ($dupSlot) {
            throw ValidationException::withMessages([
                'hora' => 'Este empleado ya tiene otra asignación ese día a esa hora.',
            ]);
        }

        $asignacion->update($data);

        return redirect()->route('asignaciones.index')->with('ok', 'Asignación actualizada');
    }

    /**
     * Elimina una asignación.
     *
     * Nota:
     * - La ruta está restringida a Administrador por middleware.
     *
     * @param  Asignacion  $asignacione
     * @return RedirectResponse
     */
    public function destroy(Asignacion $asignacione): RedirectResponse
    {
        $asignacione->delete();

        return redirect()
            ->route('asignaciones.index')
            ->with('ok', 'Asignación eliminada');
    }
}

