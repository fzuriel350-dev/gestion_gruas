<template>
    <AppLayout :title="cliente.nombre">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <Link href="/clientes" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </Link>
                <h1 class="text-xl font-bold text-gray-900">Detalles del Cliente</h1>
            </div>
            <div class="card">
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.nombre }}</p>
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
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Número de Póliza</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.numero_poliza || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de Cobertura</label>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ cliente.tipo_cobertura_poliza || '-' }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-3">Dirección</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-[10px] text-gray-400">Calle</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.calle || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Núm. Exterior</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.num_exterior || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Núm. Interior</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.num_interior || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Colonia</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.colonia || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Código Postal</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.codigo_postal || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Localidad</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.localidad || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Municipio</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.municipio || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400">Estado</label>
                                <p class="text-sm font-medium text-gray-900">{{ cliente.estado || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer flex gap-3">
                    <Link :href="`/clientes/${cliente.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="btn btn-primary">Editar</Link>
                    <button v-if="$page.props.user?.role === 'admin'" @click="eliminar" class="btn bg-red-600 text-white hover:bg-red-700">Eliminar</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    cliente: { type: Object, required: true },
});

function eliminar() {
    if (confirm('¿Estás seguro de eliminar este cliente? Esta acción no se puede deshacer.')) {
        router.delete(`/clientes/${props.cliente.id}`);
    }
}
</script>
