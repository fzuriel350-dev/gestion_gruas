<template>
    <AppLayout title="Servicios">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <h1 class="text-xl font-bold text-gray-900">Servicios</h1>
            <Link href="/servicios/create" class="btn btn-primary">Nuevo Servicio</Link>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Buscar</label>
                        <input type="text" v-model="filters.search" class="input" placeholder="Folio, cliente..." @input="filter">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select v-model="filters.estado" class="input" @change="filter">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="asignado">Asignado</option>
                            <option value="en_ruta">En ruta</option>
                            <option value="en_sitio">En sitio</option>
                            <option value="cargando">Cargando</option>
                            <option value="en_transporte">En transporte</option>
                            <option value="finalizado">Finalizado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Cliente</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Origen</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Destino</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Operador</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="s in servicios.data" :key="s.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50"><strong>{{ s.folio }}</strong></td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ s.cliente?.nombre || s.cliente?.name || s.cotizacion?.cliente?.nombre || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 max-w-[120px] truncate">{{ s.origen_direccion }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 max-w-[120px] truncate">{{ s.destino_direccion }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="'status-' + statusClass(s.estado)">
                                    <span class="status-dot"></span> {{ s.estado }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ s.operador?.nombre || s.operador?.name || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link :href="`/servicios/${s.id}`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100">Ver</Link>
                                    <Link :href="`/servicios/${s.id}/edit`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold" :style="{ color: 'var(--geg-yellow-dark)', background: 'var(--geg-yellow-light, #fef3c7)' }">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="servicios.data.length === 0">
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">No hay servicios registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100" v-if="servicios.meta">
                <Pagination :meta="servicios.meta" :links="servicios.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    servicios: { type: Object, required: true },
});

const filters = ref({
    search: '',
    estado: '',
});

let timeout = null;
function filter() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/servicios', filters.value, { preserveState: true, replace: true });
    }, 400);
}

function statusClass(estado) {
    const map = {
        pendiente: 'default',
        asignado: 'info',
        en_ruta: 'warning',
        en_sitio: 'warning',
        cargando: 'warning',
        en_transporte: 'info',
        finalizado: 'success',
        cancelado: 'danger',
    };
    return map[estado?.toLowerCase()] || 'default';
}
</script>
