<template>
    <AppLayout title="Tipos de Servicio">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <h1 class="text-xl font-bold text-gray-900">Tipos de Servicio</h1>
            <Link href="/tipos-servicio/create" class="btn btn-primary" v-if="$page.props.user?.role === 'admin'">Nuevo Tipo</Link>
        </div>

        <div class="card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Descripción</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="t in tipos.data" :key="t.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ t.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-gray-600 max-w-xs truncate">{{ t.descripcion || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link :href="`/tipos-servicio/${t.id}`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100">Ver</Link>
                                    <Link :href="`/tipos-servicio/${t.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold" :style="{ color: 'var(--geg-yellow-dark)', background: 'var(--geg-yellow-light, #fef3c7)' }">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="tipos.data.length === 0">
                            <td colspan="3" class="px-5 py-10 text-center text-gray-400 text-sm">No hay tipos de servicio registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100" v-if="tipos.meta">
                <Pagination :meta="tipos.meta" :links="tipos.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    tipos: { type: Object, required: true },
});
</script>
