<template>
    <AppLayout :title="cliente.nombre">
        <div class="max-w-3xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Detalles del Cliente</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.nombre }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.empresa || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.contacto || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.telefono || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.email || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aseguradora</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.aseguradora?.nombre || '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dirección</label>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.direccion || '-' }}</p>
                    </div>
                </div>
                <div class="card-footer flex gap-3">
                    <Link :href="`/clientes/${cliente.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary">Editar</Link>
                    <Link href="/clientes" class="btn btn-ghost">Volver</Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    cliente: { type: Object, required: true },
});
</script>
