<template>
    <AppLayout title="Notificaciones">
        <div class="card">
            <div class="card-header">
                <h3>Notificaciones</h3>
            </div>
            <div class="card-body py-2">
                <div v-for="n in notificaciones.data" :key="n.id"
                    class="flex items-start gap-3 px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shrink-0"
                        :class="tipoClass(n.tipo)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 leading-tight">{{ n.mensaje }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ formatDateTime(n.created_at) }}</p>
                    </div>
                    <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full shrink-0"
                        :class="tipoBadgeClass(n.tipo)">{{ n.tipo }}</span>
                </div>
                <div v-if="!notificaciones.data.length" class="text-center py-8 text-sm text-gray-400">
                    No tienes notificaciones
                </div>
            </div>
            <div v-if="notificaciones.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ notificaciones.meta.from }} a {{ notificaciones.meta.to }} de {{ notificaciones.meta.total }}
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
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDateTime } from '@/helpers.js';

defineProps({
    notificaciones: { type: Object, required: true },
});

function tipoClass(tipo) {
    const map = {
        info: 'bg-blue-100 text-blue-600',
        exito: 'bg-green-100 text-green-600',
        advertencia: 'bg-yellow-100 text-yellow-600',
        error: 'bg-red-100 text-red-600',
    };
    return map[tipo] || 'bg-gray-100 text-gray-600';
}

function tipoBadgeClass(tipo) {
    const map = {
        info: 'bg-blue-50 text-blue-600',
        exito: 'bg-green-50 text-green-600',
        advertencia: 'bg-yellow-50 text-yellow-600',
        error: 'bg-red-50 text-red-600',
    };
    return map[tipo] || 'bg-gray-50 text-gray-600';
}
</script>
