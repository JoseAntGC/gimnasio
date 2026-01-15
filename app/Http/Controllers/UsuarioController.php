<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controlador de Usuarios (clientes).
 *
 * Reglas de acceso:
 * - Administrador: puede ver/crear/editar/eliminar usuarios de cualquier gimnasio.
 * - Monitor: puede ver/crear/editar usuarios SOLO de su gimnasio. No puede cambiar el gimnasio de un usuario.
 * - Limpieza: no debería acceder (se bloqueará con 403 por middleware/CheckRol).
 */
class UsuarioController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     * Configura el middleware de autenticación y restricción de roles.
     */
     public function __construct()
    {
        // Admin y Monitor pueden gestionar usuarios
        $this->middleware(['auth','rol:Administrador,Monitor']);
    }

    /**
     * Valida si el empleado actual tiene permiso para interactuar con un usuario específico.
     * * @param  Usuario  $usuario
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 si no tiene permisos.
     */
    private function assertMismoGimnasio(Usuario $usuario): void
    {
        $empleado = auth('web')->user();

        // Admin puede todo
        if ($empleado->rol === 'Administrador') {
            return;
        }

        // Monitor: solo su gimnasio
        if ($empleado->rol === 'Monitor') {
            if ((int)$usuario->id_gimnasio !== (int)$empleado->id_gimnasio) {
                abort(403);
            }
            return; //si está OK, salir
        }

        // Si Limpieza llegase aquí por error:
        abort(403);
    }

    /**
     * Muestra el listado de usuarios.
     * * Los administradores ven todos los usuarios, mientras que los monitores
     * solo ven los usuarios pertenecientes a su gimnasio.
     * * @return View
     */
    public function index()
    {
        $empleado = auth('web')->user();

        $query = Usuario::with('gimnasio')->orderBy('apellidos');

        // Admin: filtra por gimnasio activo si existe
        if ($empleado->rol === 'Administrador' && session('gimnasio_activo')) {
            $query->where('id_gimnasio', session('gimnasio_activo'));
        }

        // Monitor: solo su gimnasio
        if ($empleado->rol === 'Monitor') {
            $query->where('id_gimnasio', $empleado->id_gimnasio);
        }

        $usuarios = $query->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }


    /**
     * Muestra el formulario para crear un nuevo usuario.
     * * @return View
     */
    public function create()
    {
        $empleado = auth('web')->user();

        $gimnasios = ($empleado->rol === 'Administrador')
            ? Gimnasio::orderBy('nombre')->get()
            : collect(); // monitor no necesita select

        return view('usuarios.create', compact('gimnasios','empleado'));
    }

    /**
     * Almacena un usuario recién creado en la base de datos.
     * * @param  Request  $r
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $r)
    {
         $empleado = auth('web')->user();

        $rules = [
            'nombre'    => ['required','string','max:100'],
            'apellidos' => ['required','string','max:150'],
            'DNI'       => ['required','string','max:20','unique:usuarios,DNI'],
            'email'     => ['required','email','max:150','unique:usuarios,email'],
            'telefono'  => ['required','string','max:20'],
            'password'  => ['required','string','min:6'],
            'activo'    => ['required','boolean'],
            'categoria' => ['required','in:Principiante,Intermedio,Experto'],
        ];

        // Solo Admin elige gimnasio
        if ($empleado->rol === 'Administrador') {
            $rules['id_gimnasio'] = ['required','integer','exists:gimnasio,id_gimnasio'];
        }

        $data = $r->validate($rules);

        if ($empleado->rol === 'Monitor') {
            $data['id_gimnasio'] = $empleado->id_gimnasio;
        }

        $data['password'] = Hash::make($data['password']);

        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('ok','Usuario creado');
    }

    /** 
     * Muestra el formulario de edición para un usuario específico.
     * * @param  Usuario  $usuario
     * @return View
     */
    public function edit(Usuario $usuario)
    {
       $this->assertMismoGimnasio($usuario);

        $empleado = auth('web')->user();

        $gimnasios = ($empleado->rol === 'Administrador')
            ? Gimnasio::orderBy('nombre')->get()
            : collect();

        return view('usuarios.edit', compact('usuario','gimnasios','empleado'));
    }

    /**
     * Actualiza un usuario específico en la base de datos.
     * * @param  Request  $r
     * @param  Usuario  $usuario
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $r, Usuario $usuario)
    {
        $this->assertMismoGimnasio($usuario);

        $empleado = auth('web')->user();

        $rules = [
            'nombre'    => ['required','string','max:100'],
            'apellidos' => ['required','string','max:150'],
            'DNI'       => ['required','string','max:20', Rule::unique('usuarios','DNI')->ignore($usuario->id_usuario,'id_usuario')],
            'email'     => ['required','email','max:150', Rule::unique('usuarios','email')->ignore($usuario->id_usuario,'id_usuario')],
            'telefono'  => ['required','string','max:20'],
            'password'  => ['nullable','string','min:6'],
            'activo'    => ['required','boolean'],
            'categoria' => ['required','in:Principiante,Intermedio,Experto'],
        ];

        if ($empleado->rol === 'Administrador') {
            $rules['id_gimnasio'] = ['required','integer','exists:gimnasio,id_gimnasio'];
        }

        $data = $r->validate($rules);

        // Monitor: jamás puede cambiar de gimnasio al usuario
        if ($empleado->rol === 'Monitor') {
            unset($data['id_gimnasio']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('ok','Usuario actualizado');
    }

    /**
     * Elimina un usuario específico de la base de datos.
     * * @param  Usuario  $usuario
     * @return RedirectResponse
     */
    public function destroy(Usuario $usuario)
    {
        $this->assertMismoGimnasio($usuario);

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('ok','Usuario eliminado');
    }
}
