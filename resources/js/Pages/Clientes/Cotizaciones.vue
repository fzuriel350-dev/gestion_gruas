<template>
    <AppLayout title="Mis Cotizaciones">
        <div class="card">
            <div class="card-header">
                <h3>Mis Cotizaciones</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Origen</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Destino</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Costo</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estatus</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in cotizaciones.data" :key="c.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ c.folio }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.origen || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.destino || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.costo ? formatCurrency(c.costo) : '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="`status-${estatusClass(c.estatus)}`">
                                    <span class="status-dot"></span> {{ c.estatus }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ formatDate(c.fecha || c.created_at) }}</td>
                        </tr>
                        <tr v-if="!cotizaciones.data.length">
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No tienes cotizaciones</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="cotizaciones.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ cotizaciones.meta.from }} a {{ cotizaciones.meta.to }} de {{ cotizaciones.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in cotizaciones.meta.links" :key="link.label"
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
import { formatDate, formatCurrency } from '@/helpers.js';

defineProps({
    cotizaciones: { type: Object, required: true },
});

function estatusClass(estatus) {
    const map = {
        pendiente: 'pending',
        aprobada: 'success',
        rechazada: 'danger',
        vencida: 'danger',
        convertida: 'success',
    };
    return map[estatus?.toLowerCase()] || 'pending';
}
</script>
