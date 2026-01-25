<?php

namespace App\Http\Controllers;

use App\Models\Gimnasio;
use App\Models\Empleado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

/**
 * Clase GimnasioController
 * * Gestiona el mantenimiento (CRUD) de los centros deportivos.
 * Acceso restringido únicamente a usuarios con el rol 'Administrador'.
 * * @package App\Http\Controllers
 */
class GimnasioController extends Controller
{
    /**
     * Solo el rol Administrador puede gestionar gimnasios.
     */
    public function __construct()
    {
        $this->middleware(['auth','rol:Administrador']);
    }

    /**
     * Listado paginado de gimnasios.
     */
    public function index(): View
    {
        $gimnasios = Gimnasio::orderBy('nombre')->paginate(10);
        return view('gimnasios.index', compact('gimnasios'));
    }

    /**
     * Formulario de creación.
     */
    public function create(): View
    {
        return view('gimnasios.create');
    }

    /**
     * Guarda un nuevo gimnasio.
     */
    public function store(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'nombre'    => ['required','string','max:120'],
            'cif'       => ['required','string','max:20', 'unique:gimnasio,cif'],
            'direccion' => ['nullable','string','max:255'],
            'telefono'  => ['nullable','string','max:20'],
            'email'     => ['nullable','email','max:150'],
            'activo'    => ['required','boolean'],
        ]);

        Gimnasio::create($data);

        return redirect()->route('gimnasios.index')->with('ok','Gimnasio creado');
    }

    /**
     * Formulario de edición.
     * Regla: el CIF NO se edita.
     */
    public function edit(Gimnasio $gimnasio): View
    {
        return view('gimnasios.edit', compact('gimnasio'));
    }

     /**
     * Actualiza un gimnasio.
     * Regla: el CIF NO se edita.
     * Regla: no se puede desactivar si tiene empleados o usuarios activos.
     */   
    public function update(Request $r, Gimnasio $gimnasio)
    {
        $data = $r->validate([
            'nombre' => ['required','string','max:120'],
            'direccion' => ['nullable','string','max:255'],
            // CIF NO se valida aquí si no lo dejas editar (mejor ni lo envíes en el form)
            'activo' => ['required','boolean'],
        ]);

        // Si lo están intentando desactivar...
        if ((int)$data['activo'] === 0) {

            $tieneEmpleadosActivos = Empleado::where('id_gimnasio', $gimnasio->id_gimnasio)
                ->where('activo', 1)
                ->exists();

            $tieneUsuariosActivos = Usuario::where('id_gimnasio', $gimnasio->id_gimnasio)
                ->where('activo', 1)
                ->exists();

            if ($tieneEmpleadosActivos || $tieneUsuariosActivos) {
                return back()
                    ->withErrors(['activo' => 'No se puede desactivar el gimnasio: tiene empleados o usuarios activos.'])
                    ->withInput();
            }
        }

        $gimnasio->update($data);

        return redirect()->route('gimnasios.index')->with('ok', 'Gimnasio actualizado');
    }
    
}
