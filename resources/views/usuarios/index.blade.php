@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>
    @endif
    <div class="card" x-data="tablaUsuarios()" x-init="cargar(1)">
        <div class="card-header">
            <h3>Usuarios</h3>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <input type="text" x-model="q" @input.debounce.300ms="cargar(1)" placeholder="Buscar por nombre o email..." class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg w-56">
                    <select x-model="rol" @change="cargar(1)" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg">
                        <option value="">Todos los roles</option>
                        <option value="admin">Admin</option>
                        <option value="cotizador">Cotizador</option>
                        <option value="operador">Operador</option>
                        <option value="cliente">Cliente</option>
                    </select>
                    <button x-show="q || rol" @click="q = ''; rol = ''; cargar(1)" class="btn btn-sm btn-ghost">Limpiar</button>
                </div>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Empleado vinculado</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody x-html="filas">
                </tbody>
            </table>
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
function tablaUsuarios() {
    return {
        q: '{{ request('q') }}',
        rol: '{{ request('rol') }}',
        filas: '',
        paginacion: '',
        loading: false,
        async cargar(pagina) {
            this.loading = true;
            const params = new URLSearchParams({ page: pagina, q: this.q, rol: this.rol });
            const res = await fetch(`{{ route('usuarios.buscar') }}?${params}`);
            const data = await res.json();
            this.filas = data.filas;
            this.paginacion = data.paginacion;
            this.loading = false;
            history.replaceState(null, '', `{{ route('usuarios.index') }}?${params}`);
        }
    }
}
</script>
@endpush
