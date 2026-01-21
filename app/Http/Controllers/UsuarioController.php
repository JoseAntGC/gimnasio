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
    * @param  Usuario  $usuario
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
     * Muestra el listado de usuarios con búsqueda y orden.
     *
     * - Búsqueda opcional por: nombre, apellidos, email, DNI, teléfono.
     * - Orden: activos primero, después apellidos y nombre.
     * - Administrador:
     *   - Si existe session('gimnasio_activo'), filtra por ese gimnasio.
     *   - Si no existe, muestra todos.
     * - Monitor:
     *   - Solo usuarios de su gimnasio.
     */
    public function index(Request $r)
    {
        $empleado = auth('web')->user();

        $estado = $r->query('estado'); // activa | inactiva | null
        $q      = trim((string) $r->query('q'));

        $query = Usuario::query()->with('gimnasio');

        // ===== Control por gimnasio =====
        if ($empleado->rol === 'Administrador' && session('gimnasio_activo')) {
            $query->where('usuarios.id_gimnasio', session('gimnasio_activo'));
        } elseif ($empleado->rol === 'Monitor') {
            $query->where('usuarios.id_gimnasio', $empleado->id_gimnasio);
        }

        // ===== Estado =====
        if ($estado === 'activa') {
            $query->where('usuarios.activo', 1);
        } elseif ($estado === 'inactiva') {
            $query->where('usuarios.activo', 0);
        }

        // ===== Buscador =====
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('usuarios.apellidos', 'like', "%{$q}%")
                    ->orWhere('usuarios.nombre', 'like', "%{$q}%")
                    ->orWhere('usuarios.DNI', 'like', "%{$q}%")
                    ->orWhere('usuarios.email', 'like', "%{$q}%")
                    ->orWhere('usuarios.telefono', 'like', "%{$q}%");
            });
        }

        // ===== Orden =====
        // Activos primero, luego apellidos y nombre
        $query->orderByDesc('usuarios.activo')
            ->orderBy('usuarios.apellidos')
            ->orderBy('usuarios.nombre');

        $usuarios = $query->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }



    /**
    * Muestra el formulario para crear un nuevo usuario.
    * @return View
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
    * @param  Request  $r
    * @return RedirectResponse
    * @throws \Illuminate\Validation\ValidationException
    */
    public function store(Request $r)
    {
        $empleado = auth('web')->user();
        
        // Normalización (consistencia + evita duplicados raros)
        $r->merge([
            'DNI'      => strtoupper(trim((string) $r->input('DNI'))),
            'telefono' => preg_replace('/\s+/', '', (string) $r->input('telefono')),
            'email'    => strtolower(trim((string) $r->input('email'))),
        ]);

        $rules = [
            'nombre'    => ['required','string','max:100'],
            'apellidos' => ['required','string','max:150'],
            'DNI'       => ['required','regex:/^[0-9]{8}[A-Za-z]$/','unique:usuarios,DNI'],
            'email'     => ['required','email:filter','max:150','lowercase','unique:usuarios,email'],
            'telefono'  => ['required','regex:/^[6789][0-9]{8}$/'],
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
    * @param  Usuario  $usuario
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
    * @param  Request  $r
    * @param  Usuario  $usuario
    * @return RedirectResponse
    * @throws \Illuminate\Validation\ValidationException
    */
    public function update(Request $r, Usuario $usuario)
    {
        $this->assertMismoGimnasio($usuario);

        $empleado = auth('web')->user();

         // Normalización
        $r->merge([
            'DNI'      => strtoupper(trim((string) $r->input('DNI'))),
            'telefono' => preg_replace('/\s+/', '', (string) $r->input('telefono')),
            'email'    => strtolower(trim((string) $r->input('email'))),
        ]);

        $rules = [
            'nombre'    => ['required','string','max:100'],
            'apellidos' => ['required','string','max:150'],
            'DNI'       => ['required','regex:/^[0-9]{8}[A-Za-z]$/', Rule::unique('usuarios','DNI')->ignore($usuario->id_usuario,'id_usuario')],
            'email'     => ['required','email:filter','max:150','lowercase', Rule::unique('usuarios','email')->ignore($usuario->id_usuario,'id_usuario')],
            'telefono'  => ['required','regex:/^[6789][0-9]{8}$/'],
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
   
}
