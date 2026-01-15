<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Gimnasio;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Servicios del gimnasio.
 * Solo accesible por Administradores.
 */
class ServicioController extends Controller
{
    public function __construct()
    {
        // Solo administradores
        $this->middleware(['auth', 'rol:Administrador']);
    }

    /**
     * Lista de servicios
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
     * Formulario de creación de un servicio
     */
    public function create()
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('servicios.create', compact('gimnasios'));
    }

    /**
     * Guarda un nuevo servicio
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
     * Formulario de edición de un servicio
     */
    public function edit(Servicio $servicio)
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('servicios.edit', compact('servicio','gimnasios'));
    }

    /**
     * Actualiza un servicio
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
     * Elimina un servicio
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('ok','Servicio eliminado');
    }
}
