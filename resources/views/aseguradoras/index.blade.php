@extends('layouts.app')
@section('title', 'Aseguradoras')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card" x-data="tablaAseguradoras()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Aseguradoras</h3>
            <a href="{{ route('aseguradoras.create') }}" class="btn btn-primary">+ Nueva Aseguradora</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
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
function tablaAseguradoras() {
    return { filas: '', paginacion: '', loading: false, async cargar(pagina) { this.loading = true; const res = await fetch(`{{ route('aseguradoras.buscar') }}?page=${pagina}`); const d = await res.json(); this.filas = d.filas; this.paginacion = d.paginacion; this.loading = false; } }
}
</script>
@endpush
