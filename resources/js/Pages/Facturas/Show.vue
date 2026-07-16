<template>
    <AppLayout :title="'Factura ' + factura.folio">
        <div class="flex items-center gap-4 mb-5">
            <Link href="/facturas" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Factura {{ factura.folio }}</h1>
            <div class="ml-auto flex gap-2">
                <a :href="`/facturas/${factura.id}/descargar-pdf`" target="_blank" class="btn btn-primary">Descargar PDF</a>
            </div>
        </div>

        <div class="card max-w-4xl mx-auto">
            <div class="card-body">
                <div class="flex items-start justify-between pb-6 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ empresa?.nombre || 'Grúas & Equipos' }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ empresa?.direccion || '' }}</p>
                        <p class="text-sm text-gray-500">{{ empresa?.telefono_contacto || '' }}</p>
                        <p class="text-sm text-gray-500">{{ empresa?.email_contacto || '' }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-2xl font-bold text-gray-900">FACTURA</h3>
                        <p class="text-sm text-gray-500 mt-1">Folio: {{ factura.folio }}</p>
                        <p class="text-sm text-gray-500">Fecha: {{ formatDate(factura.fecha || factura.created_at) }}</p>
                        <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full mt-2 inline-block" :class="estadoClass(factura.estado)">{{ factura.estado }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 py-6 border-b border-gray-100">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</label>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ factura.cliente?.nombre || factura.cliente?.name || '-' }}</p>
                        <p class="text-sm text-gray-500">{{ factura.cliente?.direccion || '' }}</p>
                        <p class="text-sm text-gray-500">{{ factura.cliente?.telefono || '' }}</p>
                        <p class="text-sm text-gray-500">{{ factura.cliente?.email || '' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</label>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ factura.servicio?.folio || factura.cotizacion?.folio || '-' }}</p>
                        <p class="text-sm text-gray-500">{{ factura.servicio?.tipo_servicio?.nombre || factura.cotizacion?.tipo_servicio?.nombre || '' }}</p>
                        <p class="text-sm text-gray-500">{{ factura.servicio?.descripcion || factura.cotizacion?.descripcion || '' }}</p>
                    </div>
                </div>

                <div class="py-6 border-b border-gray-100">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 pb-3">Concepto</th>
                                <th class="text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 pb-3">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in factura.conceptos || factura.detalles || []" :key="i">
                                <td class="py-2 text-sm text-gray-900">{{ item.descripcion || item.concepto || '-' }}</td>
                                <td class="py-2 text-sm text-gray-900 text-right font-medium">{{ formatCurrency(item.importe || item.monto || 0) }}</td>
                            </tr>
                            <tr v-if="!factura.conceptos && !factura.detalles">
                                <td class="py-2 text-sm text-gray-900">Servicio de grúa</td>
                                <td class="py-2 text-sm text-gray-900 text-right font-medium">{{ formatCurrency(factura.subtotal || factura.monto || 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 space-y-1.5">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ formatCurrency(factura.subtotal || factura.monto || 0) }}</span>
                    </div>
                    <div v-if="factura.iva" class="flex justify-between text-sm text-gray-600">
                        <span>IVA</span>
                        <span>{{ formatCurrency(factura.iva) }}</span>
                    </div>
                    <div v-if="factura.descuento" class="flex justify-between text-sm text-gray-600">
                        <span>Descuento</span>
                        <span>-{{ formatCurrency(factura.descuento) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total</span>
                        <span>{{ formatCurrency(factura.total || factura.monto || 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDate, formatCurrency } from '@/helpers.js';

const props = defineProps({
    factura: { type: Object, required: true },
});

const page = usePage();
const empresa = computed(() => page.props.empresa);

function estadoClass(estado) {
    const map = { pagada: 'bg-green-50 text-green-600', pendiente: 'bg-yellow-50 text-yellow-600', cancelada: 'bg-red-50 text-red-600', vencida: 'bg-red-50 text-red-600' };
    return map[estado?.toLowerCase()] || 'bg-gray-50 text-gray-600';
}
</script>
