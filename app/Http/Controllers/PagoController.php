<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Clase PagoController
 *
 * Gestiona el registro y la visualización de los pagos asociados a las suscripciones.
 * Implementa una capa de seguridad adicional para asegurar que los monitores solo
 * operen con suscripciones pertenecientes a su propio gimnasio.
 */
class PagoController extends Controller
{
    /**
     * Crea una nueva instancia del controlador y define los permisos.
     */
    public function __construct()
    {
        $this->middleware(['auth','rol:Administrador,Monitor']);
    }

   /**
     * Comprueba que el empleado (monitor) solo opere sobre suscripciones de su gimnasio.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return void
     */
    private function assertMismoGimnasio(Suscripcion $suscripcion): void
    {
        $empleado = auth('web')->user();

        if ($empleado->rol === 'Monitor' && (int)$suscripcion->id_gimnasio !== (int)$empleado->id_gimnasio) {
            abort(403);
        }
    }

    /**
     * Lista los pagos de una suscripción concreta con paginación.
     *
     * Seguridad:
     * - Si el usuario es Monitor, la suscripción debe pertenecer a su mismo gimnasio.
     *
     * @param  \App\Models\Suscripcion  $suscripcion  (Route Model Binding).
     * @return \Illuminate\View\View
     */
    public function index(Suscripcion $suscripcion)
    {
        $this->assertMismoGimnasio($suscripcion);

        $pagos = Pago::where('id_suscripcion', $suscripcion->id_suscripcion)
            ->orderByDesc('periodo')
            ->orderByDesc('id_pago')
            ->paginate(10);

        return view('pagos.index', compact('suscripcion','pagos'));
    }

    /**
     * Muestra el formulario para registrar un nuevo pago.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return \Illuminate\View\View
     */
    public function create(Suscripcion $suscripcion)
    {
        $this->assertMismoGimnasio($suscripcion);

        // Sugerencia: podrías pre-rellenar importe con el precio actual de la suscripción.
        // $importeSugerido = $suscripcion->precio;

        return view('pagos.create', compact('suscripcion'));
    }

    /**
     * Almacena un nuevo registro de pago en la base de datos.
     *
     * Reglas:
     * - 'periodo' se introduce como "Y-m" (ej: 2026-01).
     * - Internamente se guarda como DATE (YYYY-MM-01) para mantener consistencia.
     * - No se permite pagar dos veces el mismo periodo para la misma suscripción.
     *
     * @param  \Illuminate\Http\Request   $r
     * @param  \App\Models\Suscripcion    $suscripcion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $r, Suscripcion $suscripcion)
    {
        $this->assertMismoGimnasio($suscripcion);

        $data = $r->validate([
            'periodo'        => ['required', 'date_format:Y-m'], // ej: 2026-01
            'fecha_pago'     => ['required', 'date'],            // si tu columna es DATETIME cambia a date_format:Y-m-d H:i
            'importe'        => ['required', 'numeric', 'between:0,9999.99'],
            'metodo'         => ['required', 'in:efectivo,tarjeta,transferencia,bizum'],

            'estado'         => ['nullable', 'string', 'max:30'],
            'referencia'     => ['nullable', 'string', 'max:100'],
            'observaciones'  => ['nullable', 'string', 'max:255'],
        ]);

        // Convertimos "Y-m" -> "Y-m-01" para guardar como DATE (periodo mensual)
        $periodoDate = $data['periodo'] . '-01';

        // Validación amigable: evitar duplicado de periodo por suscripción
        $existe = Pago::where('id_suscripcion', $suscripcion->id_suscripcion)
            ->whereDate('periodo', $periodoDate)
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'periodo' => 'Ya existe un pago registrado para ese periodo en esta suscripción.',
            ]);
        }

        $data['id_suscripcion'] = $suscripcion->id_suscripcion;
        $data['periodo'] = $periodoDate;

        try {
            Pago::create($data);
        } catch (\Throwable $e) {
            // Fallback: si justo se coló por carrera (dos usuarios a la vez),
            // devolvemos un mensaje amigable (por el UNIQUE id_suscripcion+periodo).
            throw ValidationException::withMessages([
                'periodo' => 'No se pudo registrar: ya existe un pago para ese periodo (posible duplicado).',
            ]);
        }

        return redirect()
            ->route('pagos.index', $suscripcion)
            ->with('ok', 'Pago registrado correctamente.');
    }

     /**
     * Muestra el formulario de edición de un pago existente.
     *
     * Reglas de seguridad:
     * - El pago debe pertenecer a la suscripción indicada.
     * - Un Monitor solo puede editar pagos de suscripciones de su gimnasio.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @param  \App\Models\Pago         $pago
     * @return \Illuminate\View\View
     */
    public function edit(Suscripcion $suscripcion, Pago $pago): View
    {
        // Verificar que el pago pertenece a la suscripción
        if ($pago->id_suscripcion !== $suscripcion->id_suscripcion) {
            abort(404);
        }

        // Seguridad por gimnasio
        $empleado = auth('web')->user();
        if ($empleado->rol === 'Monitor' && (int)$suscripcion->id_gimnasio !== (int)$empleado->id_gimnasio) {
            abort(403);
        }

        return view('pagos.edit', compact('suscripcion', 'pago'));
    }

    /**
     * Actualiza un pago existente.
     *
     * Reglas importantes:
     * - NO se permite modificar el periodo (evita duplicados).
     * - NO se permite modificar el importe (valor histórico).
     * - Se puede actualizar estado, método, fecha de pago, referencia y observaciones.
     *
     * @param  \Illuminate\Http\Request $r
     * @param  \App\Models\Suscripcion  $suscripcion
     * @param  \App\Models\Pago         $pago
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $r, Suscripcion $suscripcion, Pago $pago): RedirectResponse
    {
        // Verificar relación correcta
        if ($pago->id_suscripcion !== $suscripcion->id_suscripcion) {
            abort(404);
        }

        // Seguridad por gimnasio
        $empleado = auth('web')->user();
        if ($empleado->rol === 'Monitor' && (int)$suscripcion->id_gimnasio !== (int)$empleado->id_gimnasio) {
            abort(403);
        }

        $data = $r->validate([
            'fecha_pago'    => ['nullable', 'date'],
            'metodo'        => ['required', 'in:efectivo,tarjeta,transferencia,bizum'],
            'estado'        => ['required', 'in:pagado,pendiente'],
            'referencia'    => ['nullable', 'string', 'max:100'],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ]);

        /*
         * Reglas automáticas:
         * - Si el estado pasa a "pagado" y no hay fecha, se asigna hoy.
         * - Si el estado es "pendiente", se puede dejar fecha nula.
         */
        if ($data['estado'] === 'pagado' && empty($data['fecha_pago'])) {
            $data['fecha_pago'] = now();
        }

        if ($data['estado'] === 'pendiente') {
            $data['fecha_pago'] = null;
        }

        $pago->update($data);

        return redirect()
            ->route('pagos.index', $suscripcion)
            ->with('ok', 'Pago actualizado correctamente.');
    }
}
