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
        $empresaArr = $empresa->toArray();
        $empresaArr['logo'] = $empresa->imageUrl($empresaArr['logo']);
        $empresaArr['imagen_fondo'] = $empresa->imageUrl($empresaArr['imagen_fondo']);
        $empresaArr['favicon'] = $empresa->imageUrl($empresaArr['favicon']);

        $accesosRapidos = $empresa->accesosRapidos()->get()->map(function ($a) {
            return [
                'id' => $a->id,
                'titulo' => $a->titulo,
                'icono' => $a->icono,
                'icono_imagen_url' => $a->icono_imagen_url,
                'url' => $a->url,
                'orden' => $a->orden,
                'activo' => $a->activo,
            ];
        });

        return Inertia::render('Configuracion/Index', [
            'empresa' => $empresaArr,
            'accesosRapidos' => $accesosRapidos,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('admin');
        $empresa = Empresa::findOrFail(session('empresa_id'));

        $data = $request->validate([
            'nombre' => 'nullable|string|max:150',
            'slogan' => 'nullable|string|max:255',
            'quienes_somos' => 'nullable|string',
            'mision' => 'nullable|string',
            'vision' => 'nullable|string',
            'valores' => 'nullable|string',
            'prioridad' => 'nullable|string',
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
            'imagen_fondo' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
        ]);

        $data['modo_oscuro'] = $request->boolean('modo_oscuro');
        $data['mostrar_precios'] = $request->boolean('mostrar_precios');
        $data['notificaciones_habilitadas'] = $request->boolean('notificaciones_habilitadas');

        if ($request->has('valores')) {
            $valores = $request->input('valores');
            $data['valores'] = is_string($valores) ? array_filter(array_map('trim', explode("\n", $valores))) : $valores;
        }
        if ($request->has('prioridad')) {
            $prioridad = $request->input('prioridad');
            $data['prioridad'] = is_string($prioridad) ? array_filter(array_map('trim', explode("\n", $prioridad))) : $prioridad;
        }

        if (empty($data['nombre'])) {
            $data['nombre'] = $empresa->nombre;
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('empresas', 'public');
        } else {
            unset($data['logo']);
        }
        if ($request->hasFile('imagen_fondo')) {
            $data['imagen_fondo'] = $request->file('imagen_fondo')->store('empresas/fondos', 'public');
        } else {
            unset($data['imagen_fondo']);
        }
        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('empresas', 'public');
        } else {
            unset($data['favicon']);
        }

        $empresa->update($data);

        Cache::forget("empresa_{$empresa->id}");
        Cache::forget('empresa_default');
        Cache::forget("servicios_activos_{$empresa->id}");

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
