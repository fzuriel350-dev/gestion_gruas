<template>
    <AppLayout title="Facturas">
        <div class="card">
            <div class="card-header">
                <h3>Todas las facturas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Cliente</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Monto</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="factura in facturas.data" :key="factura.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ factura.folio }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ factura.cliente?.nombre || factura.cliente?.name || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-semibold">{{ formatCurrency(factura.monto) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-gray-500">{{ formatDate(factura.fecha || factura.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full" :class="estadoClass(factura.estado)">{{ factura.estado }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <div class="flex gap-2">
                                    <Link :href="`/facturas/${factura.id}`" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">Ver</Link>
                                    <a :href="`/facturas/${factura.id}/pdf`" target="_blank" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Descargar PDF</a>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!facturas.data.length">
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No hay facturas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="facturas.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ facturas.meta.from }} a {{ facturas.meta.to }} de {{ facturas.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in facturas.meta.links" :key="link.label"
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
    facturas: { type: Object, required: true },
});

function estadoClass(estado) {
    const map = { pagada: 'bg-green-50 text-green-600', pendiente: 'bg-yellow-50 text-yellow-600', cancelada: 'bg-red-50 text-red-600', vencida: 'bg-red-50 text-red-600' };
    return map[estado?.toLowerCase()] || 'bg-gray-50 text-gray-600';
}
</script>
