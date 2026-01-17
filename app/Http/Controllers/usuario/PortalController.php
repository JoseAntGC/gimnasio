<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Clase PortalController
 * * Gestiona el área privada del usuario final (socio), incluyendo el acceso al panel principal,
 * la gestión del perfil, actualización de seguridad y descarga de recursos (rutinas).
 */
class PortalController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     * Aplica el middleware de autenticación específico para el guard 'usuario'.
     */
    public function __construct()
    {
        // Requiere estar autenticado con el guard 'usuario'
        $this->middleware('auth:usuario');
    }

    /**
     * Muestra el panel principal del usuario.
     * @return \Illuminate\View\View Vista del dashboard del usuario.
     */
    public function panel()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.panel', compact('usuario'));
    }

    /**
     * Muestra el perfil del usuario autenticado.
     * @return \Illuminate\View\View Vista con los datos del perfil.
     */
    public function perfil()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.perfil', compact('usuario'));
    }

    /**
     * Actualiza la contraseña del usuario tras validar las credenciales actuales.
     * @param  \Illuminate\Http\Request  $r Petición con 'password_actual', 'password' y 'password_confirmation'.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error de validación.
     */
    public function updatePassword(Request $r)
    {
        $r->validate([
            'password_actual' => ['required'],
            'password'        => ['required','min:6','confirmed'],
        ]);

        $usuario = Auth::guard('usuario')->user();

        if (!Hash::check($r->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $usuario->password = Hash::make($r->password);
        $usuario->save();

        return back()->with('ok', 'Contraseña actualizada correctamente.');
    }

    /**
     * Lista las rutinas disponibles para el usuario según su categoría asignada.
     * Escanea el directorio de almacenamiento correspondiente a la categoría del usuario
     * y genera URLs públicas para cada archivo encontrado.
     *
     * @return \Illuminate\View\View Vista con la colección de archivos de rutina.
     */
    public function rutinas()
    {
        $usuario   = Auth::guard('usuario')->user();
        $categoria = $usuario->categoria ?? 'Principiante'; // si no tienes el campo, se usa esta por defecto

        // Archivos en: storage/app/public/rutinas/{Categoria}/...
        $dir = storage_path('app/public/rutinas/'.$categoria);

        $archivos = [];
        if (File::isDirectory($dir)) {
            $archivos = collect(File::files($dir))
                ->map(fn($f) => [
                    'nombre' => $f->getFilename(),
                    'url'    => asset('storage/rutinas/'.$categoria.'/'.$f->getFilename()),
                ])->toArray();
        }

        return view('usuario.rutinas', compact('usuario','categoria','archivos'));
    }
}
