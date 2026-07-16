<template>
    <AppLayout title="Cotizaciones">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <h1 class="text-xl font-bold text-gray-900">Cotizaciones</h1>
            <Link href="/cotizaciones/create" class="btn btn-primary">Nueva Cotización</Link>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Buscar</label>
                        <input type="text" v-model="filters.search" class="input" placeholder="Folio, cliente..." @input="filter">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Aseguradora</label>
                        <select v-model="filters.aseguradora_id" class="input" @change="filter">
                            <option value="">Todas</option>
                            <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo de servicio</label>
                        <select v-model="filters.tipo_servicio_id" class="input" @change="filter">
                            <option value="">Todos</option>
                            <option v-for="t in tiposServicio" :key="t.id" :value="t.id">{{ t.nombre }}</option>
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
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Costo</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estatus</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in cotizaciones.data" :key="c.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50"><strong>{{ c.folio }}</strong></td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.cliente?.nombre || c.cliente?.name || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 max-w-[140px] truncate">{{ c.origen_direccion }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 max-w-[140px] truncate">{{ c.destino_direccion }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ formatCurrency(c.costo_total, moneda) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="'status-' + statusClass(c.estatus)">
                                    <span class="status-dot"></span> {{ c.estatus }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ formatDateTime(c.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link :href="`/cotizaciones/${c.id}`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100">Ver</Link>
                                    <Link :href="`/cotizaciones/${c.id}/edit`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold" :style="{ color: 'var(--geg-yellow-dark)', background: 'var(--geg-yellow-light, #fef3c7)' }">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="cotizaciones.data.length === 0">
                            <td colspan="8" class="px-5 py-10 text-center text-gray-400 text-sm">No hay cotizaciones registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100" v-if="cotizaciones.meta">
                <Pagination :meta="cotizaciones.meta" :links="cotizaciones.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatDateTime, formatCurrency } from '@/helpers.js';

const page = usePage();
const props = defineProps({
    cotizaciones: { type: Object, required: true },
    aseguradoras: { type: Array, default: () => [] },
    tiposServicio: { type: Array, default: () => [] },
});

const moneda = page.props.empresa?.moneda || '$';

const filters = ref({
    search: '',
    aseguradora_id: '',
    tipo_servicio_id: '',
});

let timeout = null;
function filter() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/cotizaciones', filters.value, { preserveState: true, replace: true });
    }, 400);
}

function statusClass(estatus) {
    const map = { pendiente: 'warning', aprobada: 'success', rechazada: 'danger', convertida: 'info' };
    return map[estatus?.toLowerCase()] || 'default';
}
</script>
