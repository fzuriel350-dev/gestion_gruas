<template>
    <AppLayout title="Clientes">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between gap-4">
                    <h3>Todos los clientes</h3>
                    <Link href="/clientes/create" class="btn btn-primary" v-if="$page.props.user?.role === 'admin'">Nuevo Cliente</Link>
                </div>
            </div>
            <div class="p-4 border-b border-gray-100">
                <form method="GET" action="/clientes" class="flex gap-3">
                    <input type="text" name="q" :value="$page.props.query || ''" placeholder="Buscar clientes..." class="form-input flex-1" />
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Empresa</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Contacto</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Teléfono</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Aseguradora</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Servicios</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in clientes.data" :key="cliente.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50 font-medium">{{ cliente.nombre }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ cliente.empresa || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ cliente.contacto || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ cliente.telefono || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ cliente.aseguradora?.nombre || '-' }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ cliente.servicios_count ?? 0 }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <div class="flex gap-2">
                                    <Link :href="`/clientes/${cliente.id}`" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">Ver</Link>
                                    <Link :href="`/clientes/${cliente.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Editar</Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!clientes.data.length">
                            <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400">No se encontraron clientes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="clientes.meta?.last_page > 1" class="card-footer flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Mostrando {{ clientes.meta.from }} a {{ clientes.meta.to }} de {{ clientes.meta.total }} registros
                </div>
                <div class="flex gap-1">
                    <Link v-for="link in clientes.meta.links" :key="link.label"
                        :href="link.url || '#'"
                        class="px-3 py-1.5 text-sm rounded-lg border"
                        :class="link.active ? 'bg-gray-900 text-white border-gray-900' : 'text-gray-600 border-gray-200 hover:bg-gray-50'"
                        v-html="link.label" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    clientes: { type: Object, required: true },
});
</script>
