<template>
    <AppLayout title="Editar Usuario">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Editar Usuario</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input v-model="form.name" type="text" class="input w-full" :class="{ 'input-error': form.errors.name }" />
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" class="input w-full" :class="{ 'input-error': form.errors.email }" />
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                            <select v-model="form.role" class="input w-full" :class="{ 'input-error': form.errors.role }">
                                <option value="admin">Admin</option>
                                <option value="cotizador">Cotizador</option>
                                <option value="operador">Operador</option>
                                <option value="cliente">Cliente</option>
                            </select>
                            <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                            <select v-model="form.empleado_id" class="input w-full" :class="{ 'input-error': form.errors.empleado_id }">
                                <option :value="null">Sin empleado</option>
                                <option v-for="emp in empleados" :key="emp.id" :value="emp.id">{{ emp.nombre }} {{ emp.apellido_paterno }}</option>
                            </select>
                            <div v-if="form.errors.empleado_id" class="text-red-500 text-xs mt-1">{{ form.errors.empleado_id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-gray-400 font-normal">(dejar vacío para mantener)</span></label>
                            <input v-model="form.password" type="password" class="input w-full" :class="{ 'input-error': form.errors.password }" />
                            <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/usuarios" class="btn btn-secondary">Cancelar</Link>
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
    usuario: { type: Object, required: true },
    empleados: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.usuario.name,
    email: props.usuario.email,
    role: props.usuario.role,
    empleado_id: props.usuario.empleado_id,
    password: '',
});

function update() {
    form.put(`/usuarios/${props.usuario.id}`);
}
</script>
