<template>
    <AppLayout :title="'Autorización ' + (autorizacionCancelacion.folio || autorizacionCancelacion.id)">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-5">
                <Link href="/autorizaciones-cancelacion" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </Link>
                <h1 class="text-xl font-bold text-gray-900">Autorización {{ autorizacionCancelacion.folio || autorizacionCancelacion.id }}</h1>
                <div class="ml-auto flex gap-2">
                    <Link :href="`/autorizaciones-cancelacion/${autorizacionCancelacion.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary">Resolver</Link>
                </div>
            </div>

            <div class="card">
                <div class="card-body space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Folio</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ autorizacionCancelacion.folio || autorizacionCancelacion.id }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</label>
                            <p class="mt-1">
                                <span class="text-[10px] font-semibold uppercase px-2 py-1 rounded-full" :class="estadoClass(autorizacionCancelacion.estado)">{{ autorizacionCancelacion.estado }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ autorizacionCancelacion.servicio?.folio || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Solicitante</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ autorizacionCancelacion.solicitante?.name || autorizacionCancelacion.solicitante?.nombre || autorizacionCancelacion.user?.name || '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</label>
                        <p class="text-sm text-gray-900 mt-1 bg-gray-50 rounded-lg p-4">{{ autorizacionCancelacion.motivo || 'Sin motivo especificado' }}</p>
                    </div>

                    <div v-if="autorizacionCancelacion.resolucion" class="border-t border-gray-100 pt-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Resolución</label>
                        <p class="text-sm text-gray-900 mt-1 bg-gray-50 rounded-lg p-4">{{ autorizacionCancelacion.resolucion }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Creada</label>
                            <p class="text-sm text-gray-900 mt-1">{{ formatDateTime(autorizacionCancelacion.created_at) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actualizada</label>
                            <p class="text-sm text-gray-900 mt-1">{{ formatDateTime(autorizacionCancelacion.updated_at) }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer flex gap-3">
                    <Link href="/autorizaciones-cancelacion" class="btn btn-ghost">Volver</Link>
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
    autorizacionCancelacion: { type: Object, required: true },
});

function estadoClass(estado) {
    const map = { pendiente: 'bg-yellow-50 text-yellow-600', aprobada: 'bg-green-50 text-green-600', rechazada: 'bg-red-50 text-red-600' };
    return map[estado?.toLowerCase()] || 'bg-gray-50 text-gray-600';
}
</script>
