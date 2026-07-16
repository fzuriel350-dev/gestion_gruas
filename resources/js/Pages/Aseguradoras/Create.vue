<template>
    <AppLayout title="Nueva Aseguradora">
        <div class="mb-5">
            <Link href="/aseguradoras" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver a aseguradoras</Link>
        </div>

        <div class="card max-w-2xl">
            <div class="card-header">
                <h3>Nueva Aseguradora</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label class="form-label">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" v-model="form.nombre" class="input" :class="{ 'border-red-400': form.errors.nombre }" placeholder="Nombre de la aseguradora">
                        <div v-if="form.errors.nombre" class="text-xs text-red-500 mt-1">{{ form.errors.nombre }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" v-model="form.telefono" class="input" :class="{ 'border-red-400': form.errors.telefono }" placeholder="Teléfono">
                        <div v-if="form.errors.telefono" class="text-xs text-red-500 mt-1">{{ form.errors.telefono }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipos de Servicio <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1">
                            <label v-for="t in tiposServicio" :key="t.id"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg border cursor-pointer text-sm"
                                :class="form.tipos_servicio.includes(t.id) ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="checkbox" :value="t.id" v-model="form.tipos_servicio"
                                    class="w-4 h-4 rounded border-gray-300" :style="{ accentColor: 'var(--geg-yellow)' }">
                                {{ t.nombre }}
                            </label>
                        </div>
                        <div v-if="form.errors.tipos_servicio" class="text-xs text-red-500 mt-1">{{ form.errors.tipos_servicio }}</div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">
                            {{ form.processing ? 'Guardando...' : 'Guardar' }}
                        </button>
                        <Link href="/aseguradoras" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</Link>
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
    tiposServicio: { type: Array, default: () => [] },
});

const form = useForm({
    nombre: '',
    telefono: '',
    tipos_servicio: [],
});

function submit() {
    form.post('/aseguradoras');
}
</script>
