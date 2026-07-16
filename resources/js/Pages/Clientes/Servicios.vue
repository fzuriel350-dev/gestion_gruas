<template>
    <AppLayout title="Mis Servicios">
        <div class="card">
            <div class="card-header">
                <h3>Mis Servicios</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="svc in servicios.data" :key="svc.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ svc.folio }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="`status-${estadoClass(svc.estado)}`">
                                    <span class="status-dot"></span> {{ svc.estado }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ formatDate(svc.fecha || svc.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <Link :href="`/panel/servicios/${svc.id}`" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">Ver</Link>
                            </td>
                        </tr>
                        <tr v-if="!servicios.data.length">
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">No tienes servicios registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="servicios.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ servicios.meta.from }} a {{ servicios.meta.to }} de {{ servicios.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in servicios.meta.links" :key="link.label"
                        :href="link.url || '#'"
                        class="px-3 py-1.5 text-sm rounded-lg border"
                        :class="link.active ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-600 border-gray-200 hover:bg-gray-50'"
                        v-html="link.label" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDate } from '@/helpers.js';

defineProps({
    servicios: { type: Object, required: true },
});

function estadoClass(estado) {
    const map = {
        'asignado': 'pending',
        'inicio_servicio': 'progress',
        'en_sitio_origen': 'progress',
        'en_carga': 'progress',
        'en_transito': 'progress',
        'en_sitio_destino': 'progress',
        'finalizado': 'success',
        'cancelado': 'danger',
    };
    return map[estado] || 'pending';
}
</script>
