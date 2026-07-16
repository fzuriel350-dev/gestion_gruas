<template>
    <AppLayout title="Empleados">
        <div class="card">
            <div class="card-header">
                <h3>Empleados</h3>
                <Link href="/empleados/create" class="btn btn-primary text-xs" v-if="$page.props.user?.role === 'admin'">Nuevo Empleado</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Puesto</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Oficina</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Teléfono</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Email</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emp in empleados.data" :key="emp.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ emp.nombre }} {{ emp.apellido_paterno }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ emp.puesto }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ emp.oficina?.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ emp.telefono }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ emp.usuario?.email }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <Link :href="`/empleados/${emp.id}`" class="text-blue-600 hover:text-blue-800 font-medium text-xs mr-3">Ver</Link>
                                <Link :href="`/empleados/${emp.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</Link>
                            </td>
                        </tr>
                        <tr v-if="empleados.data.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No hay empleados registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                <Pagination :meta="empleados.meta" :links="empleados.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    empleados: { type: Object, required: true },
});
</script>
