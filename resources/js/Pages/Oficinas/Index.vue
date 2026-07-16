<template>
    <AppLayout title="Oficinas">
        <div class="card">
            <div class="card-header">
                <h3>Oficinas</h3>
                <Link href="/oficinas/create" class="btn btn-primary text-xs" v-if="$page.props.user?.role === 'admin'">Nueva Oficina</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Dirección</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Teléfono</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ofi in oficinas.data" :key="ofi.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ ofi.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ ofi.direccion || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ ofi.telefono || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <Link :href="`/oficinas/${ofi.id}`" class="text-blue-600 hover:text-blue-800 font-medium text-xs mr-3">Ver</Link>
                                <Link :href="`/oficinas/${ofi.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</Link>
                            </td>
                        </tr>
                        <tr v-if="oficinas.data.length === 0">
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">No hay oficinas registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                <Pagination :meta="oficinas.meta" :links="oficinas.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    oficinas: { type: Object, required: true },
});
</script>
