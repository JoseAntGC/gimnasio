<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContextoGimnasioController extends Controller
{
    public function set(Request $request)
    {
        $data = $request->validate([
            'id_gimnasio' => ['required','integer','exists:gimnasio,id_gimnasio'],
        ]);

        session(['gimnasio_activo' => (int) $data['id_gimnasio']]);

        return back()->with('ok', 'Gimnasio seleccionado.');
    }

    public function clear()
    {
        session()->forget('gimnasio_activo');
        return back()->with('ok', 'Filtro de gimnasio limpiado.');
    }
}
