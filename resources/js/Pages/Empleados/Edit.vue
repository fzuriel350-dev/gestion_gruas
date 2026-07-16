<template>
    <AppLayout title="Editar Empleado">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Editar Empleado</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input v-model="form.nombre" type="text" class="input w-full" :class="{ 'input-error': form.errors.nombre }" />
                            <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno</label>
                            <input v-model="form.apellido_paterno" type="text" class="input w-full" :class="{ 'input-error': form.errors.apellido_paterno }" />
                            <div v-if="form.errors.apellido_paterno" class="text-red-500 text-xs mt-1">{{ form.errors.apellido_paterno }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                            <input v-model="form.apellido_materno" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Puesto</label>
                            <input v-model="form.puesto" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" class="input w-full" :class="{ 'input-error': form.errors.email }" />
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-gray-400 font-normal">(opcional)</span></label>
                            <input v-model="form.password" type="password" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                            <input v-model="form.password_confirmation" type="password" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Oficina</label>
                            <select v-model="form.oficina_id" class="input w-full">
                                <option :value="null">Sin oficina</option>
                                <option v-for="oficina in oficinas" :key="oficina.id" :value="oficina.id">{{ oficina.nombre }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                            <select v-model="form.role" class="input w-full" :class="{ 'input-error': form.errors.role }">
                                <option value="admin">Admin</option>
                                <option value="cotizador">Cotizador</option>
                                <option value="operador">Operador</option>
                            </select>
                            <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/empleados" class="btn btn-secondary">Cancelar</Link>
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
    empleado: { type: Object, required: true },
    oficinas: { type: Array, default: () => [] },
});

const form = useForm({
    nombre: props.empleado.nombre,
    apellido_paterno: props.empleado.apellido_paterno,
    apellido_materno: props.empleado.apellido_materno || '',
    puesto: props.empleado.puesto || '',
    telefono: props.empleado.telefono || '',
    email: props.empleado.usuario?.email || '',
    password: '',
    password_confirmation: '',
    oficina_id: props.empleado.oficina_id,
    role: props.empleado.usuario?.role || 'operador',
});

function update() {
    form.put(`/empleados/${props.empleado.id}`);
}
</script>
