<template>
    <AppLayout title="Detalle de la Oficina">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Detalle de la Oficina</h3>
                <Link :href="`/oficinas/${oficina.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary text-xs">Editar</Link>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.nombre }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dirección</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.direccion || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ciudad</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.ciudad || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.estado || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.telefono || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Encargado</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ oficina.encargado || '—' }}</dd>
                    </div>
                </dl>
                <div class="flex items-center gap-3 mt-6">
                    <Link href="/oficinas" class="btn btn-secondary">Volver</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    oficina: { type: Object, required: true },
});
</script>
