<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Controlador AsignacionController
 *
 * Gestiona la asignación de servicios (clases/actividades)
 * a empleados (monitores/administradores) en un día y hora concretos.
 *
 * Solo pueden acceder administradores y monitores.
 */
class AsignacionController extends Controller
{

    /**
     * Constructor del controlador.
     * * Establece las capas de seguridad y restricciones de acceso mediante middlewares:
     * * 1. **Acceso de Lectura (index):** Permite la entrada a usuarios autenticados 
     * con roles de 'Administrador' o 'Monitor'.
     * 2. **Acceso de Gestión (otros métodos):** Restringe las acciones de creación, 
     * edición y borrado exclusivamente a usuarios con el rol 'Administrador'.
     * * @return void
     */
     public function __construct()
    {
        // Ver listado: Admin y Monitor
        $this->middleware(['auth','rol:Administrador,Monitor'])->only(['index']);

        // Gestionar: SOLO Admin
        $this->middleware(['auth','rol:Administrador'])->except(['index']);
    }

    /**
     * Muestra el listado paginado de asignaciones.
     *
     * Carga además las relaciones:
     * - empleado
     * - servicio
     *
     * para poder mostrar el nombre del monitor y el nombre del servicio en la vista.
     */
    public function index()
    {
        $empleado = auth('web')->user();

        $query = Asignacion::with(['empleado','servicio'])
            ->orderBy('dia')
            ->orderBy('hora');

        // Admin: filtra por gimnasio activo si existe
        if ($empleado->rol === 'Administrador' && session('gimnasio_activo')) {
            $idGim = (int) session('gimnasio_activo');

            $query->whereHas('empleado', function ($q) use ($idGim) {
                $q->where('id_gimnasio', $idGim);
            });
        }

        // Monitor: SIEMPRE su gimnasio
        if ($empleado->rol === 'Monitor') {
            $idGim = (int) $empleado->id_gimnasio;

            $query->whereHas('empleado', function ($q) use ($idGim) {
                $q->where('id_gimnasio', $idGim);
            });
        }

        $asignaciones = $query->paginate(12);

        return view('asignaciones.index', compact('asignaciones'));
    }


     /**
     * Muestra el formulario de creación de una nueva asignación.
     *
     * Datos enviados a la vista:
     * - empleados: solo empleados activos con rol Administrador o Monitor
     * - servicios: solo servicios activos
     * - dias: lista de días válidos (coincide con el ENUM de la tabla)
     */
    public function create()
    {
        $idGim = session('gimnasio_activo'); // admin selecciona gimnasio

        $empleadosQuery = Empleado::where('activo', 1)
            ->whereIn('rol', ['Administrador','Monitor'])
            ->orderBy('apellidos');

        $serviciosQuery = Servicio::where('activo', 1)
            ->orderBy('nombre');

        if ($idGim) {
            $empleadosQuery->where('id_gimnasio', (int)$idGim);
            $serviciosQuery->where('id_gimnasio', (int)$idGim);
        }

        $empleados = $empleadosQuery->get();
        $servicios = $serviciosQuery->get();
        $dias = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];

        return view('asignaciones.create', compact('empleados','servicios','dias','idGim'));
    }


    /**
     * Guarda en la base de datos una nueva asignación.
     *
     * Reglas de negocio:
     * - Empleado y servicio deben pertenecer al MISMO gimnasio.
     * - No se permiten asignaciones duplicadas (mismo empleado, servicio, día y hora).
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'id_empleado' => ['required','integer','exists:empleado,id_empleado'],
            'id_servicio' => ['required','integer','exists:servicio,id_servicio'],
            'dia'         => ['required', Rule::in(['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'])],
            'hora'        => ['required','date_format:H:i'],
        ]);

        $idGim = session('gimnasio_activo'); // gimnasio elegido por admin (puede ser null)

        $emp = Empleado::findOrFail($data['id_empleado']);
        $srv = Servicio::findOrFail($data['id_servicio']);

        //Empleado y servicio deben ser del mismo gimnasio
        if ((int)$emp->id_gimnasio !== (int)$srv->id_gimnasio) {
            throw ValidationException::withMessages([
                'id_servicio' => 'El servicio debe pertenecer al mismo gimnasio que el empleado.'
            ]);
        }

        // Si hay gimnasio activo, obligamos a que ambos pertenezcan a ese gimnasio
        if ($idGim) {
            $idGim = (int)$idGim;

            if ((int)$emp->id_gimnasio !== $idGim) {
                abort(403);
            }
            if ((int)$srv->id_gimnasio !== $idGim) {
                abort(403);
            }
        }

        // Evitar duplicados 
        $dup = Asignacion::where([
            'id_empleado' => $data['id_empleado'],
            'id_servicio' => $data['id_servicio'],
            'dia'         => $data['dia'],
            'hora'        => $data['hora'],
        ])->exists();

        if ($dup) {
            throw ValidationException::withMessages([
                'hora' => 'Ya existe una asignación para ese empleado/servicio en ese día y hora.'
            ]);
        }

        Asignacion::create($data);

        return redirect()->route('asignaciones.index')->with('ok', 'Asignación creada');
    }


    /**
     * Muestra el formulario de edición de una asignación existente.
     *
     * Nota: el parámetro $asignacione se llama así porque Laravel
     * singulariza el nombre del recurso "asignaciones".
     */
    public function edit(Asignacion $asignacione) 
    {
        $asignacion = $asignacione;
        $empleados = Empleado::where('activo',1)->whereIn('rol',['Administrador','Monitor'])->orderBy('apellidos')->get();
        $servicios = Servicio::where('activo',1)->orderBy('nombre')->get();
        $dias = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
        return view('asignaciones.edit', compact('asignacion','empleados','servicios','dias'));
    }

    /**
     * Actualiza una asignación existente.
     *
     * Repite las mismas reglas de negocio que store():
     * - Empleado y servicio en el mismo gimnasio.
     * - No permitir duplicados, excluyendo la propia asignación.
     */
    public function update(Request $r, Asignacion $asignacione)
    {
        $asignacion = $asignacione;

        $data = $r->validate([
            'id_empleado' => ['required','integer','exists:empleado,id_empleado'],
            'id_servicio' => ['required','integer','exists:servicio,id_servicio'],
            'dia'         => ['required', Rule::in(['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'])],
            'hora'        => ['required','date_format:H:i'],
        ]);

        $emp = Empleado::findOrFail($data['id_empleado']);
        $srv = Servicio::findOrFail($data['id_servicio']);
        if ($emp->id_gimnasio !== $srv->id_gimnasio) {
            throw ValidationException::withMessages(['id_servicio'=>'El servicio debe pertenecer al mismo gimnasio que el empleado.']);
        }

        $dup = Asignacion::where($data)
            ->where('id_asignacion','<>',$asignacion->id_asignacion)
            ->exists();
        if ($dup) {
            throw ValidationException::withMessages(['hora'=>'Ya existe otra asignación idéntica.']);
        }

        $asignacion->update($data);
        return redirect()->route('asignaciones.index')->with('ok','Asignación actualizada');
    }

    /**
     * Elimina una asignación existente.
     *
     * Regla de negocio:
     * - Un empleado no puede eliminar su propia asignación.
     */
    public function destroy(Asignacion $asignacione)
    {
       $asignacione->delete();

        return redirect()
            ->route('asignaciones.index')
            ->with('ok','Asignación eliminada');
    }
}
