<template>
    <AppLayout title="Operadores">
        <div class="card">
            <div class="card-header">
                <h3>Operadores</h3>
                <Link v-if="$page.props.user?.role === 'admin'" href="/operadores/create" class="btn btn-primary text-xs">Nuevo Operador</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Empleado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Disponible</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Unidad</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="op in operadores.data" :key="op.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ op.empleado?.nombre }} {{ op.empleado?.apellido_paterno }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ op.empleado?.puesto || '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="op.disponible ? 'status-activo' : 'status-inactivo'">
                                    <span class="status-dot"></span> {{ op.disponible ? 'Disponible' : 'Ocupado' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ op.unidades?.length ? op.unidades.map(u => u.numero_economico || u.marca).join(', ') : '—' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <Link :href="`/operadores/${op.id}`" class="text-blue-600 hover:text-blue-800 font-medium text-xs mr-3">Ver</Link>
                                <Link v-if="$page.props.user?.role === 'admin' || $page.props.user?.role === 'cotizador'" :href="`/operadores/${op.id}/edit`" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</Link>
                            </td>
                        </tr>
                        <tr v-if="operadores.data.length === 0">
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">No hay operadores registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                <Pagination :meta="operadores.meta" :links="operadores.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    operadores: { type: Object, required: true },
});
</script>
