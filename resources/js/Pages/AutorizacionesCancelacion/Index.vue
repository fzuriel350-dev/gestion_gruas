<template>
    <AppLayout title="Autorizaciones de Cancelación">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between gap-4">
                    <h3>Autorizaciones de Cancelación</h3>
                    <Link href="/autorizaciones-cancelacion/create" class="btn btn-primary">Nueva solicitud</Link>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Servicio</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Solicitante</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="a in autorizaciones.data" :key="a.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ a.folio || a.id }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ a.servicio?.folio || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ a.solicitante?.name || a.solicitante?.nombre || a.user?.name || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full" :class="estadoClass(a.estado)">{{ a.estado }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-gray-500">{{ formatDateTime(a.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <div class="flex gap-2">
                                    <Link :href="`/autorizaciones-cancelacion/${a.id}`" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">Ver</Link>
                                    <Link :href="`/autorizaciones-cancelacion/${a.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Resolver</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!autorizaciones.data.length">
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No hay autorizaciones de cancelación</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="autorizaciones.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ autorizaciones.meta.from }} a {{ autorizaciones.meta.to }} de {{ autorizaciones.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in autorizaciones.meta.links" :key="link.label"
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
import { formatDateTime } from '@/helpers.js';

defineProps({
    autorizaciones: { type: Object, required: true },
});

function estadoClass(estado) {
    const map = { pendiente: 'bg-yellow-50 text-yellow-600', aprobada: 'bg-green-50 text-green-600', rechazada: 'bg-red-50 text-red-600' };
    return map[estado?.toLowerCase()] || 'bg-gray-50 text-gray-600';
}
</script>
