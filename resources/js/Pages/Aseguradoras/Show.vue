<template>
    <AppLayout title="Aseguradora">
        <div class="mb-5">
            <Link href="/aseguradoras" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver a aseguradoras</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ aseguradora.nombre }}</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Teléfono</label>
                                <p class="text-sm font-medium text-gray-900 mt-0.5">{{ aseguradora.telefono || 'No registrado' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Tipos de Servicio</label>
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                <span v-for="t in aseguradora.tipos_servicio" :key="t.id"
                                    class="inline-block px-2.5 py-1 rounded text-xs font-semibold"
                                    :style="{ background: 'var(--geg-yellow-light, #fef3c7)', color: 'var(--geg-yellow-dark)' }">
                                    {{ t.nombre }}
                                </span>
                                <span v-if="!aseguradora.tipos_servicio?.length" class="text-sm text-gray-400">Ninguno</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h3>Estadísticas</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600">Convenios</span>
                            <span class="text-lg font-bold text-gray-900">{{ aseguradora.convenios_count }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600">Cotizaciones</span>
                            <span class="text-lg font-bold text-gray-900">{{ aseguradora.cotizaciones_count }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <Link :href="`/aseguradoras/${aseguradora.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary flex-1 text-center">Editar</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    aseguradora: { type: Object, required: true },
});
</script>
