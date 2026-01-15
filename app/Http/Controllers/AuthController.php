<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la autenticación de usuarios y empleados.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     * Si ya hay sesión iniciada, redirige al panel correspondiente.
     */
     public function showLogin()
    {
        // Si ya hay empleado/admin logueado
        if (Auth::guard('web')->check()) {
            return redirect()->route('panel');
        }

        // Si ya hay usuario (cliente) logueado
        if (Auth::guard('usuario')->check()) {
            return redirect()->route('u.panel');
        }

        return view('auth.login');
    }

     /**
     * Procesa el login tanto de empleados/admin como de usuarios.
     */
    public function login(Request $request)
    {
        // Credenciales comunes para ambos guards (incluye que esté activo)
       $credentials = $request->validate([
        'email'    => ['required','email'],
        'password' => ['required'],
        ]);
        $remember = $request->boolean('remember');

        // Credenciales comunes para ambos guards (incluye que esté activo)
        $creds = [
        'email'    => $credentials['email'],
        'password' => $credentials['password'],
        'activo'   => 1, // Solo usuarios activos
        ];

       // 1) Empleados (guard web)
        if (Auth::guard('web')->attempt($creds, $remember)) {
            $request->session()->regenerate();
            $rol = Auth::guard('web')->user()->rol;
            return match ($rol) {
                'Administrador'       => redirect()->route('panel.admin'),
                'Monitor','Limpieza'  => redirect()->route('panel.empleado'),
                default               => abort(403),
            };
        }

        // 2) Usuarios (guard usuario)
        if (Auth::guard('usuario')->attempt($creds, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('u.panel');
        }

        // Si falla en ambos guards
        return back()->withErrors(['email'=>'Credenciales inválidas o cuenta inactiva.'])->onlyInput('email');
    }

     /**
     * Cierra la sesión del usuario actual (empleado o cliente).
     */
    public function logout(Request $request)
    {
        // Cerrar sesión en ambos guards si aplica
        if (Auth::guard('web')->check())     Auth::guard('web')->logout();
        if (Auth::guard('usuario')->check()) Auth::guard('usuario')->logout();

        // Invalidar la sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/'); // landing pública
    }
}
