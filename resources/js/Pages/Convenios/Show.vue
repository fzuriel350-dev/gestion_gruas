<template>
    <AppLayout title="Convenio">
        <div class="mb-5">
            <Link href="/convenios" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver a convenios</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ convenio.nombre }}</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Aseguradora</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ convenio.aseguradora?.nombre || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Tipo de Servicio</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ convenio.tipo_servicio?.nombre || '-' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Tipo</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5 capitalize">{{ convenio.tipo }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Cobertura</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ convenio.cobertura }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Tarifas</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Banderazo</label>
                                    <p class="text-xl font-bold text-gray-900 mt-1">{{ formatCurrency(convenio.costo_banderazo, moneda) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Costo por Km</label>
                                    <p class="text-xl font-bold text-gray-900 mt-1">{{ formatCurrency(convenio.costo_km, moneda) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Km Incluidos</label>
                                    <p class="text-xl font-bold text-gray-900 mt-1">{{ formatNumber(convenio.km_incluidos) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Cubre Casetas</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ convenio.cubre_casetas_peaje ? 'Sí' : 'No' }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Descuento</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ convenio.descuento }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex gap-2">
                    <Link :href="`/convenios/${convenio.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary flex-1 text-center">Editar</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatCurrency } from '@/helpers.js';

const page = usePage();
const moneda = page.props.empresa?.moneda || '$';

defineProps({
    convenio: { type: Object, required: true },
});

function formatNumber(n) {
    return new Intl.NumberFormat('es-MX').format(n || 0);
}
</script>
