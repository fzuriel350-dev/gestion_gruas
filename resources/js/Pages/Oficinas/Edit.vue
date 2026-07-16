<template>
    <AppLayout title="Editar Oficina">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Editar Oficina</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input v-model="form.nombre" type="text" class="input w-full" :class="{ 'input-error': form.errors.nombre }" />
                            <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input v-model="form.direccion" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                            <input v-model="form.ciudad" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <input v-model="form.estado" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encargado</label>
                            <input v-model="form.encargado" type="text" class="input w-full" />
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/oficinas" class="btn btn-secondary">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    oficina: { type: Object, required: true },
});

const form = useForm({
    nombre: props.oficina.nombre,
    direccion: props.oficina.direccion || '',
    ciudad: props.oficina.ciudad || '',
    estado: props.oficina.estado || '',
    telefono: props.oficina.telefono || '',
    encargado: props.oficina.encargado || '',
});

function update() {
    form.put(`/oficinas/${props.oficina.id}`);
}
</script>
