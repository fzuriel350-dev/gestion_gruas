@extends('layouts.app')
@section('title', 'Mis Servicios')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Mis Servicios</h2>
        <p class="page-description">Consulta y da seguimiento a tus servicios de grúa.</p>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-data="tablaClienteServicios()">
            <div>
                <label class="label">Buscar</label>
                <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Folio..." class="input">
            </div>
            <div>
                <label class="label">Estado</label>
                <select x-model="estado" @change="cargar(1)" class="input">
                    <option value="">Todos</option>
                    <option value="asignado">Asignado</option>
                    <option value="inicio_servicio">Inicio Servicio</option>
                    <option value="en_sitio_origen">En Sitio Origen</option>
                    <option value="en_carga">En Carga</option>
                    <option value="en_transito">En Tránsito</option>
                    <option value="en_sitio_destino">En Sitio Destino</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="label">Desde</label>
                <input type="date" x-model="fecha_desde" @change="cargar(1)" class="input">
            </div>
            <div>
                <label class="label">Hasta</label>
                <input type="date" x-model="fecha_hasta" @change="cargar(1)" class="input">
            </div>
            <div class="sm:col-span-2 lg:col-span-4 flex gap-2">
                <button x-show="q || estado || fecha_desde || fecha_hasta" @click="q=''; estado=''; fecha_desde=''; fecha_hasta=''; cargar(1)" class="btn-secondary">Limpiar</button>
            </div>
        </div>
    </div>
</div>

<div class="card" x-data="tablaClienteServicios()" x-init="cargar(1)">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Estatus</th>
                        <th>Operador</th>
                        <th>Unidad</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody x-html="filas"></tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100" x-html="paginacion"></div>
        <div x-show="loading" class="absolute inset-0 bg-white/60 flex items-center justify-center z-10" style="display: none;">
            <svg class="animate-spin h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>
    </div>
</div>

@push('scripts')
<script>
function tablaClienteServicios() {
    return {
        q: '{{ request('q') }}',
        estado: '{{ request('estado') }}',
        fecha_desde: '{{ request('fecha_desde') }}',
        fecha_hasta: '{{ request('fecha_hasta') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q, estado: this.estado, fecha_desde: this.fecha_desde, fecha_hasta: this.fecha_hasta });
            const res = await fetch(`{{ route('clientes.servicios.buscar') }}?${params}`);
            const d = await res.json();
            this.filas = d.filas;
            this.paginacion = d.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('clientes.servicios') }}?${params}`);
        }
    }
}
</script>
@endpush
@endsection
