<template>
    <AppLayout title="Detalle del Empleado">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Detalle del Empleado</h3>
                <Link :href="`/empleados/${empleado.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary text-xs">Editar</Link>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.nombre }} {{ empleado.apellido_paterno }} {{ empleado.apellido_materno }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Puesto</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.puesto || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.telefono || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.usuario?.email || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Oficina</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.oficina?.nombre || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</dt>
                        <dd class="text-sm text-gray-900 mt-1 capitalize">{{ empleado.usuario?.role || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dirección</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ empleado.direccion || '—' }}</dd>
                    </div>
                </dl>
                <div class="flex items-center gap-3 mt-6">
                    <Link href="/empleados" class="btn btn-secondary">Volver</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    empleado: { type: Object, required: true },
});
</script>
