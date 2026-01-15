<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controlador EmpleadoController
 *
 * Gestiona el CRUD de empleados del gimnasio:
 * - Administradores
 * - Monitores
 * - Personal de limpieza
 *
 * Solo los usuarios con rol "Administrador" pueden acceder a este controlador.
 */
class EmpleadoController extends Controller
{
    /**
    * Constructor: aplica middleware de autenticación y rol.
    *
    * - auth           → requiere sesión iniciada (guard web)
    * - rol:Administrador → restringe a empleados con rol Administrador
    */
    public function __construct()
    {
        $this->middleware(['auth','rol:Administrador']);
    }

    /**
     * Muestra el listado paginado de empleados.
     *
     * Carga también la relación 'gimnasio' para mostrar
     * el gimnasio al que pertenece cada empleado.
     */
    public function index()
    {
         $query = Empleado::with('gimnasio')
            ->orderBy('apellidos');

        if (session('gimnasio_activo')) {
            $query->where('id_gimnasio', session('gimnasio_activo'));
        }

        $empleados = $query->paginate(10);

        return view('empleados.index', compact('empleados'));
    }

    /**
     * Muestra el formulario de creación de un nuevo empleado.
     *
     * Envía a la vista la lista de gimnasios activos
     * para poder asignar el empleado a uno de ellos.
     */
    public function create()
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('empleados.create', compact('gimnasios'));
    }

    /**
     * Guarda en base de datos un nuevo empleado.
     *
     * Reglas de validación:
     * - DNI y email únicos en la tabla empleado.
     * - Rol debe estar dentro de la lista permitida.
     * - Contraseña con longitud mínima.
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
            'nombre'      => ['required','string','max:100'],
            'apellidos'   => ['required','string','max:150'],
            'DNI'         => ['required','string','max:20','unique:empleado,DNI'],
            'email'       => ['required','email','max:150','unique:empleado,email'],
            'telefono'    => ['required','string','max:20'],
            'password'    => ['required','string','min:6'],
            'rol'         => ['required', Rule::in(['Administrador','Monitor','Limpieza'])],
            'activo'      => ['required','boolean'],
        ]);
        // Encriptar contraseña antes de guardar
        $data['password'] = Hash::make($data['password']);

        Empleado::create($data);
        return redirect()->route('empleados.index')->with('ok','Empleado creado');
    }

    /**
     * Muestra el formulario de edición de un empleado.
     *
     * Parámetros enviados a la vista:
     * - empleado  → el empleado a editar
     * - gimnasios → listado de gimnasios para poder cambiar su asignación
     */
    public function edit(Empleado $empleado)
    {
        $gimnasios = Gimnasio::orderBy('nombre')->get();
        return view('empleados.edit', compact('empleado','gimnasios'));
    }

     /**
     * Actualiza los datos de un empleado existente.
     *
     * Reglas:
     * - DNI y email siguen siendo únicos, ignorando el propio empleado.
     * - La contraseña solo se actualiza si el campo viene relleno.
     */
    public function update(Request $r, Empleado $empleado)
    {
        $data = $r->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
            'nombre'      => ['required','string','max:100'],
            'apellidos'   => ['required','string','max:150'],
            'DNI'         => ['required','string','max:20', Rule::unique('empleado','DNI')->ignore($empleado->id_empleado,'id_empleado')],
            'email'       => ['required','email','max:150', Rule::unique('empleado','email')->ignore($empleado->id_empleado,'id_empleado')],
            'telefono'    => ['required','string','max:20'],
            'password'    => ['nullable','string','min:6'],
            'rol'         => ['required', Rule::in(['Administrador','Monitor','Limpieza'])],
            'activo'      => ['required','boolean'],
        ]);

        // Solo actualizar contraseña si se ha enviado
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // no cambiar contraseña
        }

        $empleado->update($data);
        return redirect()->route('empleados.index')->with('ok','Empleado actualizado correctamente.');
    }

    /**
     * Elimina un empleado.
     *
     * Regla adicional:
     * - Evita que un administrador elimine su propia cuenta
     *   para no dejar el sistema sin usuario de administración.
     */
    public function destroy(Empleado $empleado)
    {
        // Opcional: impedir borrar al propio admin logueado
        if (auth()->id() === $empleado->id_empleado) {
            return back()->withErrors(['self'=>'No puedes eliminar tu propia cuenta.']);
        }
        $empleado->delete();
        return redirect()->route('empleados.index')->with('ok','Empleado eliminado');
    }
}
