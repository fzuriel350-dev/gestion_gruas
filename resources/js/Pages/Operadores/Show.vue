<template>
    <AppLayout title="Detalle del Operador">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Detalle del Operador</h3>
                <Link :href="`/operadores/${operador.id}/edit`" v-if="$page.props.user?.role === 'admin' || $page.props.user?.role === 'cotizador'" class="btn btn-primary text-xs">Editar</Link>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ operador.empleado?.nombre }} {{ operador.empleado?.apellido_paterno }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disponible</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            <span class="status" :class="operador.disponible ? 'status-activo' : 'status-inactivo'">
                                <span class="status-dot"></span> {{ operador.disponible ? 'Sí' : 'No' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de Licencia</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ operador.licencia_tipo || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Vencimiento Licencia</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ operador.licencia_año_vencimiento || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Puntos Acumulados</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ operador.puntos_acumulados ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unidades Asignadas</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            <span v-if="operador.unidades?.length">{{ operador.unidades.map(u => u.marca + ' ' + (u.modelo || '')).join(', ') }}</span>
                            <span v-else>—</span>
                        </dd>
                    </div>
                </dl>
                <div class="flex items-center gap-3 mt-6">
                    <Link href="/operadores" class="btn btn-secondary">Volver</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    operador: { type: Object, required: true },
});
</script>
