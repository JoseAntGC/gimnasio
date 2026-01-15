<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirige al panel correspondiente según el rol del empleado autenticado.
     */
    public function redirectByRole()
    {
        $rol = Auth::user()->rol ?? null;
        return match ($rol) {
            'Administrador' => redirect()->route('panel.admin'),
            'Monitor', 'Limpieza' => redirect()->route('panel.empleado'),
            default => abort(403),
        };
    }

    /**
     * Muestra el panel de administrador.
     */
    public function admin()
    {
        $empleado = Auth::user();
        return view('panel.admin', compact('empleado'));
    }

    /**
     * Muestra el panel de empleado (monitor/limpieza).
     */
    public function empleado()
    {
        $empleado = Auth::user();
        return view('panel.empleado', compact('empleado'));
    }
}
