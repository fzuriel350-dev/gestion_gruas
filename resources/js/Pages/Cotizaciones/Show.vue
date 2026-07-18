<template>
    <AppLayout title="Cotización">
        <div class="flex items-center gap-4 mb-5">
            <Link href="/cotizaciones" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Cotización</h1>
            <div class="ml-auto flex gap-2">
                <Link :href="`/cotizaciones/${cotizacione.id}/edit`" class="btn btn-primary">Editar</Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 space-y-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Información general</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Folio</label>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ cotizacione.folio }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Estatus</label>
                            <p class="mt-0.5">
                                <span class="status" :class="'status-' + statusClass(cotizacione.estatus)">
                                    <span class="status-dot"></span> {{ cotizacione.estatus }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Cliente</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.cliente?.nombre || cotizacione.cliente?.name || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Aseguradora</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.aseguradora?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tipo de servicio</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.tipo_servicio?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Convenio</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.convenio?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Distancia</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.distancia_km ? cotizacione.distancia_km + ' km' : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tiempo estimado</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.tiempo_estimado_minutos ? cotizacione.tiempo_estimado_minutos + ' min' : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Incluye casetas</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.incluye_peajes ? 'Sí' : 'No' }}</p>
                        </div>
                        <div v-if="cotizacione.incluye_peajes">
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Casetas</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ cotizacione.num_casetas ? cotizacione.num_casetas + ' casetas' : '-' }} / ${{ cotizacione.costo_aprox_casetas || '0' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Direcciones</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide block mb-2">Origen</label>
                            <div class="space-y-1 text-sm text-gray-900">
                                <p>{{ cotizacione.origen_calle || '-' }}{{ cotizacione.origen_num_exterior ? ' Ext. ' + cotizacione.origen_num_exterior : '' }}{{ cotizacione.origen_num_interior ? ' Int. ' + cotizacione.origen_num_interior : '' }}</p>
                                <p>{{ cotizacione.origen_colonia ? 'Col. ' + cotizacione.origen_colonia : '' }}</p>
                                <p>{{ [cotizacione.origen_localidad, cotizacione.origen_municipio, cotizacione.origen_estado].filter(Boolean).join(', ') }}</p>
                                <p>{{ cotizacione.origen_codigo_postal ? 'C.P. ' + cotizacione.origen_codigo_postal : '' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide block mb-2">Destino</label>
                            <div class="space-y-1 text-sm text-gray-900">
                                <p>{{ cotizacione.destino_calle || '-' }}{{ cotizacione.destino_num_exterior ? ' Ext. ' + cotizacione.destino_num_exterior : '' }}{{ cotizacione.destino_num_interior ? ' Int. ' + cotizacione.destino_num_interior : '' }}</p>
                                <p>{{ cotizacione.destino_colonia ? 'Col. ' + cotizacione.destino_colonia : '' }}</p>
                                <p>{{ [cotizacione.destino_localidad, cotizacione.destino_municipio, cotizacione.destino_estado].filter(Boolean).join(', ') }}</p>
                                <p>{{ cotizacione.destino_codigo_postal ? 'C.P. ' + cotizacione.destino_codigo_postal : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Historial</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Creada</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ formatDateTime(cotizacione.created_at) }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Actualizada</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ formatDateTime(cotizacione.updated_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Desglose de costos</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Banderazo</span>
                            <span class="font-semibold text-gray-900">{{ formatCurrency(cotizacione.banderazo, moneda) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Kilometraje</span>
                            <span class="font-semibold text-gray-900">{{ formatCurrency(cotizacione.kilometraje, moneda) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Casetas</span>
                            <span class="font-semibold text-gray-900">{{ formatCurrency(cotizacione.casetas, moneda) }}</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between text-sm font-bold">
                            <span class="text-gray-800">Total</span>
                            <span class="text-lg" style="color: var(--geg-yellow-dark)">{{ formatCurrency(cotizacione.costo_total, moneda) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDateTime, formatCurrency } from '@/helpers.js';

const page = usePage();
const props = defineProps({
    cotizacione: { type: Object, required: true },
});

const moneda = page.props.empresa?.moneda || '$';

function statusClass(estatus) {
    const map = { pendiente: 'warning', aprobada: 'success', rechazada: 'danger', convertida: 'info' };
    return map[estatus?.toLowerCase()] || 'default';
}
</script>
