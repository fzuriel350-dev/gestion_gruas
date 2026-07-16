<template>
    <AppLayout title="Editar Tipo de Servicio">
        <div class="mb-5">
            <Link :href="`/tipos-servicio/${tiposServicio.id}`" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver</Link>
        </div>

        <div class="card max-w-2xl">
            <div class="card-header">
                <h3>Editar Tipo de Servicio</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label class="form-label">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" v-model="form.nombre" class="input" :class="{ 'border-red-400': form.errors.nombre }">
                        <div v-if="form.errors.nombre" class="text-xs text-red-500 mt-1">{{ form.errors.nombre }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea v-model="form.descripcion" class="input" :class="{ 'border-red-400': form.errors.descripcion }" rows="3"></textarea>
                        <div v-if="form.errors.descripcion" class="text-xs text-red-500 mt-1">{{ form.errors.descripcion }}</div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">
                            {{ form.processing ? 'Actualizando...' : 'Actualizar' }}
                        </button>
                        <Link :href="`/tipos-servicio/${tiposServicio.id}`" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    tiposServicio: { type: Object, required: true },
});

const form = useForm({
    nombre: props.tiposServicio.nombre,
    descripcion: props.tiposServicio.descripcion || '',
});

function submit() {
    form.put(`/tipos-servicio/${props.tiposServicio.id}`);
}
</script>
