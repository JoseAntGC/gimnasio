<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Clase ContextoGimnasioController
 * Gestiona el contexto del gimnasio seleccionado por el usuario dentro de la sesión actual.
 * Permite establecer un gimnasio activo para filtrar datos globalmente o limpiar dicho filtro.
 */
class ContextoGimnasioController extends Controller
{
    /**
     * Establece el ID del gimnasio en la sesión del usuario.
     * * Valida que el ID proporcionado sea un entero y exista en la base de datos.
     * Una vez validado, lo guarda en la clave 'gimnasio_activo' de la sesión.
     *
     * @param  \Illuminate\Http\Request  $request Objeto de la petición con el campo 'id_gimnasio'.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página anterior con mensaje de éxito.
     * * @throws \Illuminate\Validation\ValidationException Si la validación falla.
     */
    public function set(Request $request)
    {
        $data = $request->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
        ]);

        session(['gimnasio_activo' => (int) $data['id_gimnasio']]);

        return back()->with('ok', 'Gimnasio seleccionado.');
    }

    /**
     * Elimina el gimnasio activo de la sesión.
     * * Limpia la clave 'gimnasio_activo' para que la aplicación deje de filtrar por un gimnasio específico.
     *
     * @return \Illuminate\Http\RedirectResponse Redirección a la página anterior con mensaje de éxito.
     */
    public function clear()
    {
        session()->forget('gimnasio_activo');
        return back()->with('ok', 'Filtro de gimnasio limpiado.');
    }
}
