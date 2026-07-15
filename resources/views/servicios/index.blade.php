@extends('layouts.app')
@section('title', 'Servicios')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card" x-data="tablaServicios()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Servicios</h3>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Buscar por folio, cliente, operador..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all w-56">
                    <select x-model="estado" @change="cargar(1)" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
                        <option value="">Todos los estados</option>
                        <option value="asignado">Asignado</option>
                        <option value="inicio_servicio">Inicio Servicio</option>
                        <option value="en_sitio_origen">En Sitio Origen</option>
                        <option value="en_carga">En Carga</option>
                        <option value="en_transito">En Tránsito</option>
                        <option value="en_sitio_destino">En Sitio Destino</option>
                        <option value="finalizado">Finalizado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    <button x-show="q || estado" @click="q=''; estado=''; cargar(1)" class="btn btn-sm btn-ghost">Limpiar</button>
                </div>
                @if (auth()->user()->isAdmin() || auth()->user()->isCotizador())
                <a href="{{ route('servicios.create') }}" class="btn btn-primary">+ Nuevo Servicio</a>
                @endif
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Cotización</th>
                        <th>Operador</th>
                        <th>Unidad</th>
                        <th>Oficina</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
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
function tablaServicios() {
    return {
        q: '{{ request('q') }}',
        estado: '{{ request('estado') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q, estado: this.estado });
            const res = await fetch(`{{ route('servicios.buscar') }}?${params}`);
            const data = await res.json();
            this.filas = data.filas;
            this.paginacion = data.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('servicios.index') }}?${params}`);
        }
    }
}
</script>
@endpush