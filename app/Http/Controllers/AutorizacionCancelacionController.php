<?php

namespace App\Http\Controllers;

use App\Models\AutorizacionCancelacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AutorizacionCancelacionController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $autorizaciones = AutorizacionCancelacion::with('servicio.cotizacion.cliente', 'usuarioSolicitante', 'usuarioResolutor')
            ->orderByDesc('fecha_solicitud')
            ->paginate(15);

        return Inertia::render('AutorizacionesCancelacion/Index', ['autorizaciones' => $autorizaciones]);
    }

    public function buscar(Request $request)
    {
        $this->authorize('empleado');
        $autorizaciones = AutorizacionCancelacion::with('servicio.cotizacion.cliente', 'usuarioSolicitante', 'usuarioResolutor')
            ->orderByDesc('fecha_solicitud')
            ->paginate(15);

    }

    public function create()
    {
        $this->authorize('empleado');
        $servicios = Servicio::where('empresa_id', session('empresa_id'))
            ->with('cotizacion.cliente')
            ->orderByDesc('id')
            ->get();
        return Inertia::render('AutorizacionesCancelacion/Create', ['servicios' => $servicios]);
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'motivo_cancelacion' => 'required|string',
            'tipo_incidencia' => 'required|in:' . implode(',', AutorizacionCancelacion::TIPOS_INCIDENCIA),
        ]);
        $data['usuario_solicitante_id'] = auth()->id();
        $data['empresa_id'] = session('empresa_id');
        $data['fecha_solicitud'] = now();
        $data['estatus'] = 'pendiente';
        AutorizacionCancelacion::create($data);
        return redirect()->route('autorizaciones-cancelacion.index')->with('success', 'Solicitud de cancelación registrada.');
    }

    public function show(AutorizacionCancelacion $autorizacionCancelacion)
    {
        $this->authorize('empleado');
        $autorizacionCancelacion->load('servicio.cotizacion.cliente', 'servicio.operador.empleado', 'servicio.unidad', 'usuarioSolicitante', 'usuarioResolutor');
        return Inertia::render('AutorizacionesCancelacion/Show', ['autorizacionCancelacion' => $autorizacionCancelacion]);
    }

    public function edit(AutorizacionCancelacion $autorizacionCancelacion)
    {
        $this->authorize('admin');
        return Inertia::render('AutorizacionesCancelacion/Edit', ['autorizacionCancelacion' => $autorizacionCancelacion]);
    }

    public function update(Request $request, AutorizacionCancelacion $autorizacionCancelacion)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'estatus' => 'required|in:' . implode(',', AutorizacionCancelacion::ESTATUS),
        ]);
        $data['usuario_resolutor_id'] = auth()->id();
        $data['fecha_resolucion'] = now();
        $autorizacionCancelacion->update($data);

        if ($data['estatus'] === 'cancelado_por_cotizador') {
            $servicio = $autorizacionCancelacion->servicio;
            $servicio->update(['estado' => 'cancelado']);

            if ($servicio->operador_id) {
                \App\Models\Operador::where('id', $servicio->operador_id)->update(['disponible' => true]);
            }
        }

        return redirect()->route('autorizaciones-cancelacion.index')->with('success', 'Solicitud actualizada.');
    }

    public function destroy(AutorizacionCancelacion $autorizacionCancelacion)
    {
        $this->authorize('admin');
        $autorizacionCancelacion->delete();
        return redirect()->route('autorizaciones-cancelacion.index')->with('success', 'Solicitud eliminada.');
    }
}
