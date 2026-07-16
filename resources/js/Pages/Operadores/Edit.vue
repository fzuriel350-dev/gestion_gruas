<template>
    <AppLayout title="Editar Operador">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Editar Operador</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                            <select v-model="form.empleado_id" class="input w-full" :class="{ 'input-error': form.errors.empleado_id }">
                                <option v-for="emp in empleados" :key="emp.id" :value="emp.id">{{ emp.nombre }} {{ emp.apellido_paterno }}</option>
                            </select>
                            <div v-if="form.errors.empleado_id" class="text-red-500 text-xs mt-1">{{ form.errors.empleado_id }}</div>
                        </div>
                        <div>
                            <label class="flex items-center gap-3">
                                <input v-model="form.disponible" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600" />
                                <span class="text-sm font-medium text-gray-700">Disponible</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/operadores" class="btn btn-secondary">Cancelar</Link>
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
    operador: { type: Object, required: true },
    empleados: { type: Array, default: () => [] },
});

const form = useForm({
    empleado_id: props.operador.empleado_id,
    disponible: props.operador.disponible,
});

function update() {
    form.put(`/operadores/${props.operador.id}`);
}
</script>
