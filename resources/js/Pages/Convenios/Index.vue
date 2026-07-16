<template>
    <AppLayout title="Convenios">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <h1 class="text-xl font-bold text-gray-900">Convenios</h1>
            <Link href="/convenios/create" class="btn btn-primary" v-if="$page.props.user?.role === 'admin'">Nuevo Convenio</Link>
        </div>

        <div class="card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Aseguradora</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Tipo Servicio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Banderazo</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Costo Km</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Km Incl.</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Casetas</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Dto.</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in convenios.data" :key="c.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ c.aseguradora?.nombre || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.tipo_servicio?.nombre || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ c.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right font-medium">{{ formatCurrency(c.costo_banderazo, moneda) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right font-medium">{{ formatCurrency(c.costo_km, moneda) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">{{ c.km_incluidos ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-center">
                                <span :class="c.cubre_casetas_peaje ? 'text-green-600' : 'text-red-400'">{{ c.cubre_casetas_peaje ? 'Sí' : 'No' }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-center">{{ c.descuento }}%</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link :href="`/convenios/${c.id}`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100">Ver</Link>
                                    <Link :href="`/convenios/${c.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold" :style="{ color: 'var(--geg-yellow-dark)', background: 'var(--geg-yellow-light, #fef3c7)' }">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="convenios.data.length === 0">
                            <td colspan="9" class="px-5 py-10 text-center text-gray-400 text-sm">No hay convenios registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100" v-if="convenios.meta">
                <Pagination :meta="convenios.meta" :links="convenios.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatCurrency } from '@/helpers.js';

const page = usePage();
const moneda = page.props.empresa?.moneda || '$';

defineProps({
    convenios: { type: Object, required: true },
});


</script>
