<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Gimnasio;
use Illuminate\Http\Request;

/**
 * Clase ServicioController
 * * Gestiona el ciclo de vida de los servicios ofrecidos por los gimnasios.
 * Incluye funcionalidades de filtrado por contexto de sesión, validación de 
 * unicidad compuesta (nombre/gimnasio) y operaciones CRUD estándar.
 */
class ServicioController extends Controller
{
    /**
    * Crea una nueva instancia del controlador y define los permisos.
    * Requiere autenticación y el rol de 'Administrador' para todos los métodos.
    */
    public function __construct()
    {
        // Solo administradores
        $this->middleware(['auth', 'rol:Administrador']);
    }

    /**
    * Muestra el listado de servicios, permitiendo filtrado por gimnasio activo.
    * Si existe un `gimnasio_activo` en la sesión, los resultados se filtran por dicho ID.
    * @return \Illuminate\View\View Vista con la colección de servicios paginada.
    */
    public function index()
    {
        $query = Servicio::with('gimnasio')->orderBy('nombre');

        if (session('gimnasio_activo')) {
            $query->where('id_gimnasio', session('gimnasio_activo'));
        }

        $servicios = $query->paginate(10);

        return view('servicios.index', compact('servicios'));
    }


    /**
    * Muestra el formulario para crear un nuevo servicio.
    * @return \Illuminate\View\View Vista con el listado de gimnasios disponibles.
    */
    public function create()
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('servicios.create', compact('gimnasios'));
    }

    /**
    * Almacena un nuevo servicio en la base de datos.
    * Realiza una validación de unicidad manual: no permite dos servicios con el 
    * mismo nombre dentro del mismo gimnasio.
    * @param  \Illuminate\Http\Request  $request Datos del formulario.
    * @return \Illuminate\Http\RedirectResponse Redirección al índice con mensaje de éxito o error de duplicidad.
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
            'nombre'      => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:255'],
            'activo'      => ['required','boolean'],
        ]);

        // Unicidad por (gimnasio, nombre)
        $exists = Servicio::where('id_gimnasio',$data['id_gimnasio'])
            ->where('nombre',$data['nombre'])
            ->exists();
        if ($exists) {
            return back()
                ->withErrors(['nombre' => 'Ya existe un servicio con ese nombre en este gimnasio.'])
                ->withInput();
        }

        Servicio::create($data);
        return redirect()->route('servicios.index')->with('ok','Servicio creado correctamente.');
    }
    
    /**
    * Muestra el formulario de edición para un servicio existente.
    * @param  \App\Models\Servicio  $servicio Instancia del servicio (Route Model Binding).
    * @return \Illuminate\View\View Vista de edición.
    */
    public function edit(Servicio $servicio)
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('servicios.edit', compact('servicio','gimnasios'));
    }

    /**
    * Actualiza la información del servicio especificado.
    * Valida que el nombre no esté duplicado en el mismo gimnasio, 
    * omitiendo el registro actual de la comprobación.
    *
    * @param  \Illuminate\Http\Request  $request Datos actualizados.
    * @param  \App\Models\Servicio  $servicio Instancia del servicio a actualizar.
    * @return \Illuminate\Http\RedirectResponse Redirección al índice.
    */
    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
            'nombre'      => ['required','string','max:120'],
            'descripcion' => ['nullable','string','max:255'],
            'activo'      => ['required','boolean'],
        ]);

        // Comprobar duplicados excepto este mismo servicio
        $exists = Servicio::where('id_gimnasio',$data['id_gimnasio'])
            ->where('nombre',$data['nombre'])
            ->where('id_servicio','<>',$servicio->id_servicio)
            ->exists();
        if ($exists) {
            return back()
                ->withErrors(['nombre' => 'Ya existe un servicio con ese nombre en este gimnasio.'])
                ->withInput();
        }

        $servicio->update($data);
        return redirect()->route('servicios.index')->with('ok','Servicio actualizado');
    }

    /**
     * Elimina un servicio de la base de datos.
     *
     * Regla de negocio:
     * - No se permite eliminar un servicio si tiene asignaciones (empleados).
     *
     * @param  \App\Models\Servicio  $servicio
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Servicio $servicio)
    {
        // Si el servicio tiene asignaciones, no se puede eliminar
        if ($servicio->asignaciones()->exists()) {
            return redirect()
                ->route('servicios.index')
                ->with('error', 'No se puede eliminar el servicio porque tiene empleados asignados.');
        }

        try {
            $servicio->delete();
        } catch (\Throwable $e) {
            return redirect()
                ->route('servicios.index')
                ->with('error', 'No se puede eliminar el servicio porque está siendo utilizado.');
        }

        return redirect()
            ->route('servicios.index')
            ->with('ok', 'Servicio eliminado correctamente.');
    }

}
