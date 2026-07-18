<template>
    <AppLayout title="Editar Usuario">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <Link href="/usuarios" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </Link>
                <h1 class="text-xl font-bold text-gray-900">Editar Usuario</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="update" novalidate>
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                                <input v-model="form.name" type="text" class="input w-full" :class="{ 'input-error': form.errors.name }" />
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input v-model="form.email" type="email" class="input w-full" :class="{ 'input-error': form.errors.email }" />
                                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                                <select v-model="form.role" class="input w-full" :class="{ 'input-error': form.errors.role }">
                                    <option value="admin">Admin</option>
                                    <option value="cotizador">Cotizador</option>
                                    <option value="operador">Operador</option>
                                    <option value="cliente">Cliente</option>
                                </select>
                                <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                            </div>

                            <div v-if="esEmpleado">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Empleado <span class="text-red-500">*</span></label>
                                <select v-model="form.empleado_id" class="input w-full" :class="{ 'input-error': form.errors.empleado_id }">
                                    <option :value="null">Sin empleado</option>
                                    <option v-for="emp in empleados" :key="emp.id" :value="emp.id">{{ emp.nombre }} {{ emp.apellido_paterno }}</option>
                                </select>
                                <div v-if="form.errors.empleado_id" class="text-red-500 text-xs mt-1">{{ form.errors.empleado_id }}</div>
                            </div>

                            <div v-if="form.role === 'operador'" class="border-t border-gray-200 pt-4 mt-2 space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700">Datos del Operador</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Licencia <span class="text-red-500">*</span></label>
                                        <input v-model="form.licencia_tipo" type="text" class="input w-full" :class="{ 'input-error': form.errors.licencia_tipo }" />
                                        <div v-if="form.errors.licencia_tipo" class="text-red-500 text-xs mt-1">{{ form.errors.licencia_tipo }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Vencimiento Licencia Local <span class="text-red-500">*</span></label>
                                        <input v-model="form.licencia_vencimiento_local" type="date" class="input w-full" :class="{ 'input-error': form.errors.licencia_vencimiento_local }" />
                                        <div v-if="form.errors.licencia_vencimiento_local" class="text-red-500 text-xs mt-1">{{ form.errors.licencia_vencimiento_local }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Vencimiento Licencia Federal</label>
                                        <input v-model="form.licencia_vencimiento_federal" type="date" class="input w-full" :class="{ 'input-error': form.errors.licencia_vencimiento_federal }" />
                                        <div v-if="form.errors.licencia_vencimiento_federal" class="text-red-500 text-xs mt-1">{{ form.errors.licencia_vencimiento_federal }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Puntos Acumulados</label>
                                        <input v-model="form.puntos_acumulados" type="number" min="0" class="input w-full" :class="{ 'input-error': form.errors.puntos_acumulados }" />
                                        <div v-if="form.errors.puntos_acumulados" class="text-red-500 text-xs mt-1">{{ form.errors.puntos_acumulados }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input v-model="form.disponible" type="checkbox" class="sr-only peer" />
                                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-green-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                                        </label>
                                        <label class="text-sm font-medium text-gray-700 cursor-pointer select-none">Disponible</label>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.role === 'cliente'" class="border-t border-gray-200 pt-4 mt-2 space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700">Datos del Cliente</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                                        <input v-model="form.telefono" type="text" class="input w-full" :class="{ 'input-error': form.errors.telefono }" />
                                        <div v-if="form.errors.telefono" class="text-red-500 text-xs mt-1">{{ form.errors.telefono }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Aseguradora <span class="text-red-500">*</span></label>
                                        <select v-model="form.aseguradora_id" class="input w-full" :class="{ 'input-error': form.errors.aseguradora_id }">
                                            <option value="">Seleccionar aseguradora...</option>
                                            <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                                        </select>
                                        <div v-if="form.errors.aseguradora_id" class="text-red-500 text-xs mt-1">{{ form.errors.aseguradora_id }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Póliza <span class="text-red-500">*</span></label>
                                        <input v-model="form.numero_poliza" type="text" class="input w-full" :class="{ 'input-error': form.errors.numero_poliza }" />
                                        <div v-if="form.errors.numero_poliza" class="text-red-500 text-xs mt-1">{{ form.errors.numero_poliza }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cobertura <span class="text-red-500">*</span></label>
                                        <input v-model="form.tipo_cobertura_poliza" type="text" class="input w-full" :class="{ 'input-error': form.errors.tipo_cobertura_poliza }" />
                                        <div v-if="form.errors.tipo_cobertura_poliza" class="text-red-500 text-xs mt-1">{{ form.errors.tipo_cobertura_poliza }}</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Calle <span class="text-red-500">*</span></label>
                                        <input v-model="form.calle" type="text" class="input w-full" :class="{ 'input-error': form.errors.calle }" />
                                        <div v-if="form.errors.calle" class="text-red-500 text-xs mt-1">{{ form.errors.calle }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Núm. Exterior</label>
                                        <input v-model="form.num_exterior" type="text" class="input w-full" :class="{ 'input-error': form.errors.num_exterior }" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Núm. Interior</label>
                                        <input v-model="form.num_interior" type="text" class="input w-full" :class="{ 'input-error': form.errors.num_interior }" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Colonia <span class="text-red-500">*</span></label>
                                        <input v-model="form.colonia" type="text" class="input w-full" :class="{ 'input-error': form.errors.colonia }" />
                                        <div v-if="form.errors.colonia" class="text-red-500 text-xs mt-1">{{ form.errors.colonia }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Código Postal <span class="text-red-500">*</span></label>
                                        <input v-model="form.codigo_postal" type="text" class="input w-full" :class="{ 'input-error': form.errors.codigo_postal }" />
                                        <div v-if="form.errors.codigo_postal" class="text-red-500 text-xs mt-1">{{ form.errors.codigo_postal }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Localidad <span class="text-red-500">*</span></label>
                                        <input v-model="form.localidad" type="text" class="input w-full" :class="{ 'input-error': form.errors.localidad }" />
                                        <div v-if="form.errors.localidad" class="text-red-500 text-xs mt-1">{{ form.errors.localidad }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Municipio <span class="text-red-500">*</span></label>
                                        <input v-model="form.municipio" type="text" class="input w-full" :class="{ 'input-error': form.errors.municipio }" />
                                        <div v-if="form.errors.municipio" class="text-red-500 text-xs mt-1">{{ form.errors.municipio }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                                        <input v-model="form.estado" type="text" class="input w-full" :class="{ 'input-error': form.errors.estado }" />
                                        <div v-if="form.errors.estado" class="text-red-500 text-xs mt-1">{{ form.errors.estado }}</div>
                                    </div>
                                </div>
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
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    usuario: { type: Object, required: true },
    empleados: { type: Array, default: () => [] },
    aseguradoras: { type: Array, default: () => [] },
});

const operador = props.usuario.empleado?.operador;

const form = useForm({
    name: props.usuario.name,
    email: props.usuario.email,
    role: props.usuario.role,
    empleado_id: props.usuario.empleado_id,
    password: '',
    telefono: props.usuario.cliente?.telefono || '',
    aseguradora_id: props.usuario.cliente?.aseguradora_id || '',
    numero_poliza: props.usuario.cliente?.numero_poliza || '',
    tipo_cobertura_poliza: props.usuario.cliente?.tipo_cobertura_poliza || '',
    calle: props.usuario.cliente?.calle || '',
    num_exterior: props.usuario.cliente?.num_exterior || '',
    num_interior: props.usuario.cliente?.num_interior || '',
    colonia: props.usuario.cliente?.colonia || '',
    codigo_postal: props.usuario.cliente?.codigo_postal || '',
    localidad: props.usuario.cliente?.localidad || '',
    municipio: props.usuario.cliente?.municipio || '',
    estado: props.usuario.cliente?.estado || '',
    licencia_tipo: operador?.licencia_tipo || '',
    licencia_vencimiento_local: operador?.licencia_año_vencimiento || '',
    licencia_vencimiento_federal: operador?.licencia_vencimiento_federal || '',
    puntos_acumulados: operador?.puntos_acumulados || 0,
    disponible: operador?.disponible ?? true,
});

const esEmpleado = computed(() => ['admin', 'cotizador', 'operador'].includes(form.role));

function update() {
    form.put(`/usuarios/${props.usuario.id}`);
}
</script>