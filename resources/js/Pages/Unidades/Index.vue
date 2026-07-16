<template>
    <AppLayout title="Unidades">
        <div class="card">
            <div class="card-header">
                <h3>Unidades</h3>
                <Link href="/unidades/create" class="btn btn-primary text-xs">Nueva Unidad</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Unidad</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Marca</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Modelo</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Operador</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Oficina</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="u in unidades.data" :key="u.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ u.numero_economico || u.placas || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ u.marca }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ u.modelo || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ u.operador?.empleado?.nombre }} {{ u.operador?.empleado?.apellido_paterno }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ u.oficina?.nombre || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <Link :href="`/unidades/${u.id}`" class="text-blue-600 hover:text-blue-800 font-medium text-xs mr-3">Ver</Link>
                                <Link :href="`/unidades/${u.id}/edit`" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</Link>
                            </td>
                        </tr>
                        <tr v-if="unidades.data.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No hay unidades registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                <Pagination :meta="unidades.meta" :links="unidades.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    unidades: { type: Object, required: true },
});
</script>
