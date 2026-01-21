<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Servicio;
use Illuminate\Http\Request;

/**
 * Class UsuarioClasesController
 *
 * Controlador del portal de usuario para la consulta de clases / actividades.
 *
 * Funcionalidad:
 * - Permite a un usuario autenticado ver las clases (asignaciones)
 *   disponibles en su gimnasio.
 * - El usuario NO puede modificar datos, solo consultar.
 *
 * Seguridad:
 * - Acceso restringido mediante el guard `auth:usuario`.
 * - El usuario solo puede ver asignaciones de su propio gimnasio.
 *
 * Filtros disponibles:
 * - Por día de la semana.
 * - Por servicio (actividad).
 */
class UsuarioClasesController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Aplica el middleware de autenticación para usuarios
     * del portal de clientes (guard: usuario).
     */
    public function __construct()
    {
        $this->middleware('auth:usuario');
    }

    /**
     * Muestra el listado de clases disponibles para el usuario.
     *
     * Reglas de negocio:
     * - Solo se muestran asignaciones del gimnasio del usuario autenticado.
     * - Se permite filtrar por:
     *   - Día de la semana (`dia`)
     *   - Servicio (`id_servicio`)
     *
     * Ordenación:
     * - Primero por día.
     * - Después por hora.
     *
     * Paginación:
     * - Resultados paginados con conservación de filtros.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\View\View
     */
    public function index(Request $r)
    {
        $usuario = auth('usuario')->user();

        $query = Asignacion::with(['servicio', 'empleado'])
            ->whereHas('empleado', function ($q) use ($usuario) {
                $q->where('id_gimnasio', $usuario->id_gimnasio);
            });

        // 🔎 Filtro por día
        if ($r->filled('dia')) {
            $query->where('dia', $r->dia);
        }

        // 🔎 Filtro por servicio
        if ($r->filled('servicio')) {
            $query->where('id_servicio', $r->servicio);
        }

        $asignaciones = $query
            ->orderBy('dia')
            ->orderBy('hora')
            ->paginate(12)
            ->withQueryString();

        // Servicios del gimnasio (para el select)
        $servicios = Servicio::where('activo', 1)
            ->where('id_gimnasio', $usuario->id_gimnasio)
            ->orderBy('nombre')
            ->get();

        return view('usuario.clases', compact('asignaciones', 'servicios'));
    }

}
