@extends('layouts.app')
@section('title', 'Notificaciones')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Notificaciones</h2>
        <p class="page-description">Actualizaciones de cotizaciones y servicios.</p>
    </div>
    <form method="POST" action="{{ route('cotizador.notificaciones.leer-todas') }}">
        @csrf
        <button type="submit" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Marcar todas como leídas
        </button>
    </form>
</div>

<div class="card">
    <div class="px-5 py-3 border-b border-gray-100">
        <form method="GET" class="flex items-center gap-3">
            <select name="tipo" class="text-sm rounded-lg border-gray-200" onchange="this.form.submit()">
                <option value="">Todos los tipos</option>
                @foreach ($tipos as $t)
                    <option value="{{ $t }}" @selected(request('tipo') === $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                @endforeach
            </select>
            <select name="estado" class="text-sm rounded-lg border-gray-200" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="no_leida" @selected(request('estado') === 'no_leida')>No leídas</option>
                <option value="leida" @selected(request('estado') === 'leida')>Leídas</option>
            </select>
            @if (request()->anyFilled(['tipo', 'estado']))
                <a href="{{ route('cotizador.notificaciones') }}" class="text-xs text-gray-500 hover:underline">Limpiar filtros</a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        @forelse ($notificaciones as $n)
            @php
                $icono = match (true) {
                    str_starts_with($n->tipo, 'cotizacion') => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    str_starts_with($n->tipo, 'servicio') => 'M13 10V3L4 14h7v7l9-11h-7z',
                    $n->tipo === 'factura' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
                    default => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                };
                $color = match (true) {
                    str_starts_with($n->tipo, 'cotizacion') => 'bg-purple-100 text-purple-700',
                    str_starts_with($n->tipo, 'servicio') => 'bg-blue-100 text-blue-700',
                    $n->tipo === 'factura' => 'bg-emerald-100 text-emerald-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors {{ $n->estado === 'no_leida' ? 'bg-[var(--geg-yellow-light)]' : '' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $color }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icono }}" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        @if ($n->estado === 'no_leida')
                            <span class="w-2 h-2 rounded-full bg-[var(--geg-yellow)] shrink-0"></span>
                        @endif
                        <span class="text-xs font-medium {{ $color }} px-1.5 py-0.5 rounded">{{ ucfirst(str_replace('_', ' ', $n->tipo)) }}</span>
                    </div>
                    <p class="text-sm text-gray-700 {{ $n->estado === 'no_leida' ? 'font-semibold' : '' }}">{{ $n->mensaje }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if ($n->estado === 'no_leida')
                    <form method="POST" action="{{ route('cotizador.notificaciones.leer', $n) }}">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-[var(--geg-yellow-dark)] hover:text-[#8A7500] transition-colors">
                            Marcar leída
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="text-gray-500 text-sm">No tienes notificaciones.</p>
            </div>
        @endforelse
    </div>
    @if ($notificaciones->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection