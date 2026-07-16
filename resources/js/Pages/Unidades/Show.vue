<template>
    <AppLayout title="Detalle de la Unidad">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Detalle de la Unidad</h3>
                <Link :href="`/unidades/${unidad.id}/edit`" class="btn btn-primary text-xs">Editar</Link>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Marca</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.marca }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.tipo }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Modelo</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.modelo || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Año</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.año }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Placas</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.placas }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Número Económico</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.numero_economico || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Número de Serie</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.numero_serie || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado Emplacado</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.estado_emplacado || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Seguro Vencimiento</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.seguro_vencimiento || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Operador</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.operador?.empleado?.nombre }} {{ unidad.operador?.empleado?.apellido_paterno || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Oficina</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ unidad.oficina?.nombre || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Activo</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            <span class="status" :class="unidad.activo ? 'status-activo' : 'status-inactivo'">
                                <span class="status-dot"></span> {{ unidad.activo ? 'Sí' : 'No' }}
                            </span>
                        </dd>
                    </div>
                </dl>
                <div class="flex items-center gap-3 mt-6">
                    <Link href="/unidades" class="btn btn-secondary">Volver</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    unidad: { type: Object, required: true },
});
</script>
