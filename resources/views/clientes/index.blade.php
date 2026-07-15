@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card" x-data="tablaClientes()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Catálogo de clientes</h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Nuevo Cliente</a>
                <div class="flex items-center gap-2">
                    <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Buscar cliente..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all w-48">
                    <button x-show="q" @click="q = ''; cargar(1)" class="btn btn-sm btn-ghost">Limpiar</button>
                </div>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Aseguradora</th>
                        <th>Póliza</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Cotizaciones</th>
                        <th>Última actividad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody x-html="filas"></tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100" x-html="paginacion"></div>
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
function tablaClientes() {
    return {
        q: '{{ request('q') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q });
            const res = await fetch(`{{ route('clientes.buscar') }}?${params}`);
            const data = await res.json();
            this.filas = data.filas;
            this.paginacion = data.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('clientes.index') }}?${params}`);
        }
    }
}
</script>
@endpush
