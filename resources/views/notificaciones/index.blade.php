@extends('layouts.app')
@section('title', 'Notificaciones')
@section('content')
<div class="max-w-4xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="card" x-data="tablaNotificaciones()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Notificaciones</h3>
            <form method="POST" action="{{ route('notificaciones.leer-todas') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">Marcar todas como leídas</button>
            </form>
        </div>

        <div class="px-5 py-3 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <select x-model="tipo" @change="cargar(1)" class="text-sm rounded-lg border-gray-200">
                    <option value="">Todos los tipos</option>
                    @foreach ($tipos as $t)
                        <option value="{{ $t }}">{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                    @endforeach
                </select>
                <select x-model="estado" @change="cargar(1)" class="text-sm rounded-lg border-gray-200">
                    <option value="">Todos los estados</option>
                    <option value="no_leida">No leídas</option>
                    <option value="leida">Leídas</option>
                </select>
                <button x-show="tipo || estado" @click="tipo=''; estado=''; cargar(1)" class="text-xs text-gray-500 hover:underline">Limpiar filtros</button>
            </div>
        </div>

        <div class="divide-y divide-gray-100" x-html="filas">
        </div>

        <div class="px-5 py-3 border-t border-gray-100" x-html="paginacion">
        </div>
        <div x-show="loading" class="absolute inset-0 bg-white/60 flex items-center justify-center z-10" style="display: none;">
            <svg class="animate-spin h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function tablaNotificaciones() {
    return {
        tipo: '{{ request('tipo') }}',
        estado: '{{ request('estado') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, tipo: this.tipo, estado: this.estado });
            const res = await fetch(`{{ route('notificaciones.buscar') }}?${params}`);
            const data = await res.json();
            this.filas = data.filas;
            this.paginacion = data.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('notificaciones.index') }}?${params}`);
        }
    }
}
</script>
@endpush