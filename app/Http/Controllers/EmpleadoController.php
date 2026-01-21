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
    public function index(Request $r)
    {
        $empleadoAuth = auth('web')->user();

        $q = trim((string) $r->query('q', '')); // texto buscador

        $query = Empleado::with('gimnasio');

        // Administrador puede filtrar por gimnasio
        $idGim = $r->query('id_gimnasio');

        if ($empleadoAuth->rol === 'Administrador') {
            if (!empty($idGim)) {
                $query->where('empleado.id_gimnasio', (int)$idGim);
            } elseif (session('gimnasio_activo')) {
                $query->where('empleado.id_gimnasio', (int)session('gimnasio_activo'));
            }
        }

        // Buscador (nombre/apellidos/email/DNI/teléfono)
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('empleado.nombre', 'like', "%{$q}%")
                    ->orWhere('empleado.apellidos', 'like', "%{$q}%")
                    ->orWhere('empleado.email', 'like', "%{$q}%")
                    ->orWhere('empleado.DNI', 'like', "%{$q}%")
                    ->orWhere('empleado.telefono', 'like', "%{$q}%");
            });
        }

        // Orden: activos primero, luego gimnasio, apellidos, nombre
        $empleados = $query
            ->orderByDesc('empleado.activo')
            ->orderBy('empleado.id_gimnasio')
            ->orderBy('empleado.apellidos')
            ->orderBy('empleado.nombre')
            ->paginate(10)
            ->withQueryString();

        // Para el select de gimnasios en la vista (solo admin)
        $gimnasios = Gimnasio::orderBy('nombre')->get();

        return view('empleados.index', compact('empleados', 'gimnasios', 'q', 'idGim'));
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
            'DNI'         => ['required','string','regex:/^[0-9]{8}[A-Za-z]$/','unique:empleado,DNI'],
            'email'       => ['required','email:filter','max:150','lowercase','unique:empleado,email'],
            'telefono'    => ['required','regex:/^[6789][0-9]{8}$/'],
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
            'DNI'         => ['required','regex:/^[0-9]{8}[A-Za-z]$/', Rule::unique('empleado','DNI')->ignore($empleado->id_empleado,'id_empleado')],
            'email'       => ['required','email:filter','max:150','lowercase', Rule::unique('empleado','email')->ignore($empleado->id_empleado,'id_empleado')],
            'telefono'    => ['required','regex:/^[6789][0-9]{8}$/'],
            'password'    => ['nullable','string','min:6'],
            'rol'         => ['required', Rule::in(['Administrador','Monitor','Limpieza'])],
            'activo'      => ['required','boolean'],
        ]);

        // Si estoy editándome a mí mismo…
        if (auth('web')->id() === $empleado->id_empleado) {

            // No permitir desactivarse
            if (isset($data['activo']) && (int)$data['activo'] === 0) {
                return back()
                    ->withErrors(['activo' => 'No puedes desactivar tu cuenta'])
                    ->withInput();
            }

            // No permitir cambiarse de rol
            if (isset($data['rol']) && $data['rol'] !== 'Administrador') {
                return back()
                    ->withErrors(['rol' => 'No puedes cambiar tu rol.'])
                    ->withInput();
            }
          
        }

        // Solo actualizar contraseña si se ha enviado
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // no cambiar contraseña
        }

        $empleado->update($data);
        return redirect()->route('empleados.index')->with('ok','Empleado actualizado correctamente.');
    }

}
