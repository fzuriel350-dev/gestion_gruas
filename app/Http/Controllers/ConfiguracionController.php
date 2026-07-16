<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $empresa = Empresa::findOrFail(session('empresa_id'));
        return Inertia::render('Configuracion/Index', ['empresa' => $empresa]);
    }

    public function update(Request $request)
    {
        $this->authorize('admin');
        $empresa = Empresa::findOrFail(session('empresa_id'));

        \Log::debug('CONFIG UPDATE PAYLOAD', ['all' => $request->all(), 'method' => $request->method(), 'content_type' => $request->header('Content-Type')]);

        $data = $request->validate([
            'nombre' => 'nullable|string|max:150',
            'color' => 'nullable|string|max:20',
            'color_secundario' => 'nullable|string|max:20',
            'tipografia' => 'required|string|max:100',
            'modo_oscuro' => 'boolean',
            'telefono_contacto' => 'nullable|string|max:30',
            'email_contacto' => 'nullable|max:150',
            'whatsapp' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'moneda' => 'required|string|max:10',
            'formato_fecha' => 'nullable|string|max:20',
            'zona_horaria' => 'nullable|string|max:50',
            'idioma' => 'nullable|string|max:5',
            'footer_texto' => 'nullable|string|max:255',
            'mostrar_precios' => 'boolean',
            'notificaciones_habilitadas' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
        ]);

        $data['modo_oscuro'] = $request->boolean('modo_oscuro');
        $data['mostrar_precios'] = $request->boolean('mostrar_precios');
        $data['notificaciones_habilitadas'] = $request->boolean('notificaciones_habilitadas');

        if (empty($data['nombre'])) {
            $data['nombre'] = $empresa->nombre;
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('empresas', 'public');
        } else {
            unset($data['logo']);
        }
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('empresas', 'public');
        }

        $empresa->update($data);

        Cache::forget("empresa_{$empresa->id}");
        Cache::forget("servicios_activos_{$empresa->id}");

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
