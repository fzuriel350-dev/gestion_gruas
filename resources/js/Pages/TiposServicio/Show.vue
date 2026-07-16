<template>
    <AppLayout title="Tipo de Servicio">
        <div class="mb-5">
            <Link href="/tipos-servicio" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver a tipos de servicio</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ tiposServicio.nombre }}</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <label class="text-[11px] uppercase tracking-wider font-bold text-gray-500">Descripción</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ tiposServicio.descripcion || 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h3>Estadísticas</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600">Cotizaciones</span>
                            <span class="text-lg font-bold text-gray-900">{{ tiposServicio.cotizaciones_count }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <Link :href="`/tipos-servicio/${tiposServicio.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary flex-1 text-center">Editar</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    tiposServicio: { type: Object, required: true },
});
</script>
