<template>
    <AppLayout title="Notificaciones">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between gap-4">
                    <h3>Notificaciones</h3>
                    <div class="flex items-center gap-2">
                        <button @click="marcarTodasLeidas" class="btn-accent inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                Marcar todas leídas
                            </button>
                        <select v-model="filtroTipo" @change="filtrar" class="input text-sm">
                            <option value="">Todos los tipos</option>
                            <option v-for="t in tipos" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Mensaje</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Tipo</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Fecha</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="n in notificaciones.data" :key="n.id" class="hover:bg-gray-50" :class="{ 'bg-gray-50/50': n.estado !== 'leida' }">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ n.mensaje }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full" :class="tipoBadgeClass(n.tipo)">{{ n.tipo }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="text-xs font-semibold" :class="n.estado === 'leida' ? 'text-green-600' : 'text-yellow-600'">{{ n.estado === 'leida' ? 'Leída' : 'No leída' }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-gray-500">{{ formatDateTime(n.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <button v-if="n.estado !== 'leida'" @click="marcarLeida(n.id)" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">Marcar leída</button>
                            </td>
                        </tr>
                        <tr v-if="!notificaciones.data.length">
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">No hay notificaciones</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="notificaciones.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ notificaciones.meta.from }} a {{ notificaciones.meta.to }} de {{ notificaciones.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in notificaciones.meta.links" :key="link.label"
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
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDateTime } from '@/helpers.js';

const props = defineProps({
    notificaciones: { type: Object, required: true },
    tipos: { type: Array, default: () => [] },
});

const filtroTipo = ref('');

function tipoBadgeClass(tipo) {
    const map = { info: 'bg-blue-50 text-blue-600', exito: 'bg-green-50 text-green-600', advertencia: 'bg-yellow-50 text-yellow-600', error: 'bg-red-50 text-red-600' };
    return map[tipo] || 'bg-gray-50 text-gray-600';
}

function filtrar() {
    const params = {};
    if (filtroTipo.value) params.tipo = filtroTipo.value;
    router.get('/cotizador/notificaciones', params, { preserveState: true, replace: true });
}

function marcarLeida(id) {
    router.post(`/cotizador/notificaciones/${id}/leer`, {}, {
        preserveScroll: true,
    });
}

function marcarTodasLeidas() {
    router.post('/cotizador/notificaciones/leer-todas', {}, {
        preserveScroll: true,
    });
}
</script>
