<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireGimnasioContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('web')->user();

        // Solo aplica a empleados admin
        if ($user && $user->rol === 'Administrador') {
            if (!session()->has('gimnasio_activo')) {
                return redirect()->route('ctx.gimnasio.select');
            }
        }

        return $next($request);
    }
}
