<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware para comprobar que el usuario autenticado
 * tiene alguno de los roles permitidos.
 *
 * Uso en rutas: ->middleware('rol:Administrador,Monitor')
 */
class CheckRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
         $user = Auth::guard('web')->user();
        // Si no hay usuario o su rol no está permitido => 403
        if (empty($roles)) {
            abort(403);
        }

        if (!$user || !in_array($user->rol, $roles)) {
            abort(403);
        }
        return $next($request);
    }
}
