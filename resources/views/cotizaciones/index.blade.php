@extends('layouts.app')
@section('title', 'Cotizaciones')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card" x-data="tablaCotizaciones()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Todas las cotizaciones</h3>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Buscar folio, cliente..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all w-44">
                    <select x-model="aseguradora_id" @change="cargar(1)" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
                        <option value="">Todas las aseguradoras</option>
                        @foreach ($aseguradoras as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                        @endforeach
                    </select>
                    <select x-model="tipo_servicio_id" @change="cargar(1)" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
                        <option value="">Todos los servicios</option>
                        @foreach ($tiposServicio as $ts)
                        <option value="{{ $ts->id }}">{{ $ts->nombre }}</option>
                        @endforeach
                    </select>
                    <select x-model="estatus" @change="cargar(1)" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                    <select x-model="orden" @change="cargar(1)" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
                        <option value="creacion_desc">Más recientes</option>
                        <option value="creacion_asc">Más antiguos</option>
                        <option value="cliente_asc">Cliente A-Z</option>
                        <option value="cliente_desc">Cliente Z-A</option>
                        <option value="total_asc">Total menor</option>
                        <option value="total_desc">Total mayor</option>
                    </select>
                    <button x-show="q || aseguradora_id || tipo_servicio_id || estatus" @click="q=''; aseguradora_id=''; tipo_servicio_id=''; estatus=''; orden='creacion_desc'; cargar(1)" class="btn btn-sm btn-ghost">Limpiar</button>
                </div>
                @if (auth()->user()->isEmpleado())
                <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">+ Nueva Cotización</a>
                @endif
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Aseguradora</th>
                        <th>Tipo Servicio</th>
                        <th>Distancia</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
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
function tablaCotizaciones() {
    return {
        q: '{{ request('q') }}',
        aseguradora_id: '{{ request('aseguradora_id') }}',
        tipo_servicio_id: '{{ request('tipo_servicio_id') }}',
        estatus: '{{ request('estatus') }}',
        orden: '{{ request('orden', 'creacion_desc') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q, aseguradora_id: this.aseguradora_id, tipo_servicio_id: this.tipo_servicio_id, estatus: this.estatus, orden: this.orden });
            const res = await fetch(`{{ route('cotizaciones.buscar') }}?${params}`);
            const data = await res.json();
            this.filas = data.filas;
            this.paginacion = data.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('cotizaciones.index') }}?${params}`);
        }
    }
}
</script>
@endpush