<template>
    <AppLayout :title="'Resolver Autorización ' + (autorizacionCancelacion.folio || autorizacionCancelacion.id)">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Resolver solicitud de cancelación</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4 border-b border-gray-100">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Servicio</label>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ autorizacionCancelacion.servicio?.folio || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Solicitante</label>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ autorizacionCancelacion.solicitante?.name || autorizacionCancelacion.solicitante?.nombre || autorizacionCancelacion.user?.name || '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</label>
                                <p class="text-sm text-gray-900 mt-1">{{ autorizacionCancelacion.motivo }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select v-model="form.estado" class="input w-full" :class="{ 'input-error': form.errors.estado }">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobada">Aprobar</option>
                                <option value="rechazada">Rechazar</option>
                            </select>
                            <div v-if="form.errors.estado" class="text-red-500 text-xs mt-1">{{ form.errors.estado }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Resolución / Comentarios</label>
                            <textarea v-model="form.resolucion" rows="4" class="input w-full" :class="{ 'input-error': form.errors.resolucion }" placeholder="Agrega comentarios sobre la resolución..."></textarea>
                            <div v-if="form.errors.resolucion" class="text-red-500 text-xs mt-1">{{ form.errors.resolucion }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link :href="`/autorizaciones-cancelacion/${autorizacionCancelacion.id}`" class="btn btn-secondary">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    autorizacionCancelacion: { type: Object, required: true },
});

const form = useForm({
    estado: props.autorizacionCancelacion.estado || 'pendiente',
    resolucion: props.autorizacionCancelacion.resolucion || '',
});

function update() {
    form.put(`/autorizaciones-cancelacion/${props.autorizacionCancelacion.id}`);
}
</script>
