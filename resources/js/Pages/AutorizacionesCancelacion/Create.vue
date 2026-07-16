<template>
    <AppLayout title="Nueva Autorización de Cancelación">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Nueva solicitud de cancelación</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="store">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Servicio</label>
                            <select v-model="form.servicio_id" class="input w-full" :class="{ 'input-error': form.errors.servicio_id }">
                                <option :value="null">Selecciona un servicio</option>
                                <option v-for="s in servicios" :key="s.id" :value="s.id">{{ s.folio }} - {{ s.cliente?.nombre || s.cliente?.name || '' }}</option>
                            </select>
                            <div v-if="form.errors.servicio_id" class="text-red-500 text-xs mt-1">{{ form.errors.servicio_id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                            <textarea v-model="form.motivo" rows="4" class="input w-full" :class="{ 'input-error': form.errors.motivo }" placeholder="Describe el motivo de la cancelación..."></textarea>
                            <div v-if="form.errors.motivo" class="text-red-500 text-xs mt-1">{{ form.errors.motivo }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Solicitar cancelación</button>
                        <Link href="/autorizaciones-cancelacion" class="btn btn-secondary">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    servicios: { type: Array, default: () => [] },
});

const form = useForm({
    servicio_id: null,
    motivo: '',
});

function store() {
    form.post('/autorizaciones-cancelacion');
}
</script>
