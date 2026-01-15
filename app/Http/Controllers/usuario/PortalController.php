<?php

namespace App\Http\Controllers\usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class PortalController extends Controller
{
    public function __construct()
    {
        // Requiere estar autenticado con el guard 'usuario'
        $this->middleware('auth:usuario');
    }

    public function panel()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.panel', compact('usuario'));
    }

    public function perfil()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.perfil', compact('usuario'));
    }

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
