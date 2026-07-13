@extends('layouts.app')
@section('title', 'Notificaciones')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Notificaciones</h2>
        <p class="page-description">Actualizaciones de tus servicios asignados.</p>
    </div>
    <form method="POST" action="{{ route('operador.notificaciones.leer-todas') }}">
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
    <div class="card-body p-0">
        @forelse ($notificaciones as $n)
            @php
                $color = match (true) {
                    str_starts_with($n->tipo, 'servicio') => 'bg-blue-100 text-blue-700',
                    $n->tipo === 'factura' => 'bg-emerald-100 text-emerald-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors {{ $n->estado === 'no_leida' ? 'bg-[var(--geg-yellow-light)]' : '' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $color }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
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
                    <form method="POST" action="{{ route('operador.notificaciones.leer', $n) }}">
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