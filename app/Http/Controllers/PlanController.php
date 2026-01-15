<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

/**
 * Controlador de Planes.
 *
 * Permite al Administrador crear, editar y eliminar planes (tarifas) del gimnasio.
 *
 * Importante:
 * - El precio del plan se considera "precio actual".
 * - Las suscripciones guardan su propio precio (histórico). Cambiar el precio aquí
 *   NO modifica suscripciones ya existentes.
 * - Un plan NO se puede eliminar si está siendo usado por alguna suscripción.
 */
class PlanController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     *
     * Restringe el acceso a usuarios autenticados con rol Administrador.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'rol:Administrador']);
    }

    /**
     * Muestra el listado de planes.
     *
     * @return View
     */
    public function index(): View
    {
        $planes = Plan::orderBy('nombre')->paginate(10);

        return view('planes.index', compact('planes'));
    }

    /**
     * Muestra el formulario para crear un nuevo plan.
     *
     * @return View
     */
    public function create(): View
    {
        return view('planes.create');
    }

    /**
     * Guarda un nuevo plan en la base de datos.
     *
     * @param  Request  $r
     * @return RedirectResponse
     */
    public function store(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'nombre' => ['required', 'string', 'max:120', 'unique:planes,nombre'],
            'precio' => ['required', 'numeric', 'between:0,9999.99'],
            'activo' => ['required', 'boolean'],
        ]);

        Plan::create($data);

        return redirect()
            ->route('planes.index')
            ->with('ok', 'Plan creado correctamente.');
    }

    /**
     * Muestra el formulario de edición de un plan.
     *
     * @param  Plan  $plan
     * @return View
     */
    public function edit(Plan $plan): View
    {
        return view('planes.edit', compact('plan'));
    }

    /**
     * Actualiza un plan existente.
     *
     * Nota:
     * - Si cambias el precio, solo afectará a nuevas suscripciones o a suscripciones
     *   que cambien de plan.
     *
     * @param  Request  $r
     * @param  Plan     $plan
     * @return RedirectResponse
     */
    public function update(Request $r, Plan $plan): RedirectResponse
    {
        $data = $r->validate([
            'nombre' => [
                'required',
                'string',
                'max:120',
                Rule::unique('planes', 'nombre')->ignore($plan->id_plan, 'id_plan'),
            ],
            'precio' => ['required', 'numeric', 'between:0,9999.99'],
            'activo' => ['required', 'boolean'],
        ]);

        $plan->update($data);

        return redirect()
            ->route('planes.index')
            ->with('ok', 'Plan actualizado correctamente.');
    }

    /**
     * Elimina un plan.
     *
     * Regla:
     * - Si el plan está asociado a alguna suscripción, no se elimina.
     *
     * @param  Plan  $plan
     * @return RedirectResponse
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        // Si existe la relación en el modelo Plan: suscripciones()
        if (method_exists($plan, 'suscripciones') && $plan->suscripciones()->exists()) {
            return back()->with('error', 'No se puede eliminar: hay suscripciones que usan este plan.');
        }

        // Si no existe la relación, protegemos igualmente por si hay FK (try/catch)
        try {
            $plan->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'No se puede eliminar: el plan está en uso por suscripciones.');
        }

        return redirect()
            ->route('planes.index')
            ->with('ok', 'Plan eliminado correctamente.');
    }
}
