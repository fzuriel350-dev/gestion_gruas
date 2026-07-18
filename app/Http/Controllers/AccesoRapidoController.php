<?php

namespace App\Http\Controllers;

use App\Models\AccesoRapido;
use Illuminate\Http\Request;

class AccesoRapidoController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $accesos = AccesoRapido::where('empresa_id', session('empresa_id'))
            ->orderBy('orden')
            ->get()
            ->map(function ($a) {
                $arr = $a->toArray();
                $arr['icono_imagen_url'] = $a->icono_imagen_url;
                return $arr;
            });
        return response()->json($accesos);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'titulo' => 'required|string|max:100',
            'icono' => 'nullable|string|max:50',
            'icono_imagen' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:1024',
            'url' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
        ]);
        $data['empresa_id'] = session('empresa_id');
        $data['orden'] = $data['orden'] ?? 0;

        if ($request->hasFile('icono_imagen')) {
            $data['icono_imagen'] = $request->file('icono_imagen')->store('accesos_rapidos', 'public');
        }

        AccesoRapido::create($data);
        return redirect()->back();
    }

    public function update(Request $request, AccesoRapido $accesosRapido)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'titulo' => 'required|string|max:100',
            'icono' => 'nullable|string|max:50',
            'icono_imagen' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:1024',
            'url' => 'required|string|max:255',
            'orden' => 'nullable|integer|min:0',
            'activo' => 'boolean',
        ]);

        if ($request->hasFile('icono_imagen')) {
            $data['icono_imagen'] = $request->file('icono_imagen')->store('accesos_rapidos', 'public');
        } else {
            unset($data['icono_imagen']);
        }

        $accesosRapido->update($data);
        return redirect()->back();
    }

    public function destroy(AccesoRapido $accesosRapido)
    {
        $this->authorize('admin');
        $accesosRapido->delete();
        return redirect()->back();
    }
}
