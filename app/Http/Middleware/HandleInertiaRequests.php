<?php

namespace App\Http\Middleware;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'inertia';

    public function share(Request $request): array
    {
        $data = [];

        if ($request->user()) {
            $user = $request->user();
            $empresaId = session('empresa_id');

            $serviciosActivos = 0;
            $noLeidas = 0;

            $empresa = \App\Models\Empresa::find($empresaId);

            $noLeidas = \App\Models\Notificacion::where('empresa_id', $empresaId)
                ->where(function ($q) use ($user) {
                    $q->where('usuario_id', $user->id)->orWhereNull('usuario_id');
                })
                ->where('estado', 'no_leida')
                ->count();

            if ($user->isCliente()) {
                $clienteId = \App\Models\Cliente::where('usuario_id', $user->id)->value('id');
                if ($clienteId) {
                    $serviciosActivos = Servicio::where('empresa_id', $empresaId)
                        ->whereHas('cotizacion', fn($q) => $q->where('cliente_id', $clienteId))
                        ->whereIn('estado', Servicio::ESTADOS_ACTIVOS)
                        ->count();
                }
            } elseif ($user->isCotizador() || $user->isAdmin()) {
                $serviciosActivos = Servicio::where('empresa_id', $empresaId)
                    ->whereIn('estado', Servicio::ESTADOS_ACTIVOS)
                    ->count();
            }

            $data = [
                'user' => $user,
                'empresa' => $empresa,
                'serviciosActivos' => $serviciosActivos,
                'noLeidas' => $noLeidas,
                'csrf_token' => csrf_token(),
            ];
        }

        return array_merge(parent::share($request), $data);
    }
}
