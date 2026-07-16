<template>
    <AppLayout title="Aseguradoras">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <h1 class="text-xl font-bold text-gray-900">Aseguradoras</h1>
            <Link href="/aseguradoras/create" class="btn btn-primary" v-if="$page.props.user?.role === 'admin'">Nueva Aseguradora</Link>
        </div>

        <div class="card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Teléfono</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Tipos de Servicio</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="a in aseguradoras.data" :key="a.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ a.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ a.telefono || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="t in a.tipos_servicio" :key="t.id"
                                        class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold"
                                        :style="{ background: 'var(--geg-yellow-light, #fef3c7)', color: 'var(--geg-yellow-dark)' }">
                                        {{ t.nombre }}
                                    </span>
                                    <span v-if="!a.tipos_servicio?.length" class="text-gray-400">-</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <Link :href="`/aseguradoras/${a.id}`" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100">Ver</Link>
                                    <Link :href="`/aseguradoras/${a.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold" :style="{ color: 'var(--geg-yellow-dark)', background: 'var(--geg-yellow-light, #fef3c7)' }">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="aseguradoras.data.length === 0">
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">No hay aseguradoras registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100" v-if="aseguradoras.meta">
                <Pagination :meta="aseguradoras.meta" :links="aseguradoras.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    aseguradoras: { type: Object, required: true },
});
</script>
