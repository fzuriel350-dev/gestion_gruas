@extends('layouts.app')
@section('title', 'Mis Cotizaciones')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Mis Cotizaciones</h2>
        <p class="page-description">Todas tus cotizaciones de servicio de grúa.</p>
    </div>

</div>

<div class="card mb-5">
    <div class="card-body">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="label">Buscar</label>
                <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Folio, origen o destino..." class="input">
            </div>
            <div>
                <label class="label">Estatus</label>
                <select x-model="estatus" @change="cargar(1)" class="input">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button x-show="q || estatus" @click="q=''; estatus=''; cargar(1)" class="btn-secondary">Limpiar</button>
            </div>
        </div>
    </div>
</div>

<div class="card" x-data="tablaClienteCotizaciones()" x-init="cargar(1)">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Servicio</th>
                        <th>Tipo</th>
                        <th>Vehículo</th>
                        <th>Total</th>
                        <th>Estatus</th>
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
function tablaClienteCotizaciones() {
    return {
        q: '{{ request('q') }}',
        estatus: '{{ request('estatus') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q, estatus: this.estatus });
            const res = await fetch(`{{ route('clientes.cotizaciones.buscar') }}?${params}`);
            const d = await res.json();
            this.filas = d.filas;
            this.paginacion = d.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('clientes.cotizaciones') }}?${params}`);
        }
    }
}
function rechazarCotizacion(folio, id) {
    Swal.fire({
        title: '❌ Rechazar cotización',
        html: `
            <p class="text-sm text-gray-600 mb-3">Cotización #${folio}</p>
            <textarea id="motivo-rechazo" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none transition-all" placeholder="Indica por qué rechazas esta cotización (opcional)..."></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        customClass: {
            popup: 'swal-popup-custom',
            title: 'swal-title-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        },
        didRender: function() {
            document.getElementById('motivo-rechazo').focus();
        },
        preConfirm: function() {
            return document.getElementById('motivo-rechazo').value;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/panel/cotizaciones/' + id + '/rechazar';
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            if (result.value) {
                var motivo = document.createElement('input');
                motivo.type = 'hidden';
                motivo.name = 'motivo';
                motivo.value = result.value;
                form.appendChild(motivo);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
