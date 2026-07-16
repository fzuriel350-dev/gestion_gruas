<template>
    <AppLayout :title="`Servicio: ${servicio.folio}`">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="card">
                <div class="card-header">
                    <h3>Servicio {{ servicio.folio }}</h3>
                    <span class="status" :class="`status-${estadoClass}`">
                        <span class="status-dot"></span> {{ servicio.estado }}
                    </span>
                </div>
                <div class="card-body space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Origen</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ servicio.origen || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Destino</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ servicio.destino || '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Progreso del servicio</h3>
                </div>
                <div class="card-body">
                    <div class="relative">
                        <div v-for="(step, i) in pasos" :key="step.key"
                            class="flex items-start gap-4 pb-8 relative last:pb-0"
                            :class="{ 'opacity-40': !step.completed && !step.active }">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                    :class="step.completed ? 'bg-green-500 text-white' : step.active ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400'">
                                    <svg v-if="step.completed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else>{{ i + 1 }}</span>
                                </div>
                                <div v-if="i < pasos.length - 1" class="w-0.5 flex-1 mt-1"
                                    :class="step.completed ? 'bg-green-500' : 'bg-gray-200'"></div>
                            </div>
                            <div class="pt-1">
                                <p class="text-sm font-semibold" :class="step.completed ? 'text-green-600' : step.active ? 'text-blue-600' : 'text-gray-500'">
                                    {{ step.label }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <Link href="/panel/servicios" class="btn btn-ghost">Volver</Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    servicio: { type: Object, required: true },
    progreso: { type: Number, default: 0 },
});

const estados = [
    { key: 'asignado', label: 'Asignado' },
    { key: 'inicio_servicio', label: 'Inicio de servicio' },
    { key: 'en_sitio_origen', label: 'En sitio de origen' },
    { key: 'en_carga', label: 'En carga' },
    { key: 'en_transito', label: 'En tránsito' },
    { key: 'en_sitio_destino', label: 'En sitio de destino' },
    { key: 'finalizado', label: 'Finalizado' },
];

const idxActual = computed(() => {
    const idx = estados.findIndex(e => e.key === props.servicio.estado);
    return idx >= 0 ? idx : 0;
});

const pasos = computed(() => {
    if (props.servicio.estado === 'cancelado') {
        return [
            ...estados.slice(0, idxActual.value).map(e => ({ ...e, completed: true, active: false })),
            { key: 'cancelado', label: 'Cancelado', completed: false, active: true },
        ];
    }
    return estados.map((e, i) => ({
        ...e,
        completed: i < idxActual.value,
        active: i === idxActual.value,
    }));
});

const estadoClass = computed(() => {
    const map = {
        asignado: 'pending',
        inicio_servicio: 'progress',
        en_sitio_origen: 'progress',
        en_carga: 'progress',
        en_transito: 'progress',
        en_sitio_destino: 'progress',
        finalizado: 'success',
        cancelado: 'danger',
    };
    return map[props.servicio.estado] || 'pending';
});
</script>
