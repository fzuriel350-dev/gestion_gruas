<template>
    <AppLayout title="Usuarios">
        <div class="card">
            <div class="card-header">
                <h3>Usuarios</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Nombre</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Email</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Rol</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Creado</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="usuario in usuarios.data" :key="usuario.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ usuario.name }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ usuario.email }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">
                                <span class="status" :class="`status-${usuario.role}`">
                                    <span class="status-dot"></span> {{ usuario.role }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50">{{ formatDateTime(usuario.created_at) }}</td>
                            <td class="px-5 py-3 text-sm border-b border-gray-50 space-x-3">
                                <Link :href="`/usuarios/${usuario.id}/edit`" v-if="$page.props.user?.role === 'admin'" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</Link>
                                <button @click="confirmDelete(usuario)" class="text-red-600 hover:text-red-800 font-medium text-xs">Eliminar</button>
                            </td>
                        </tr>
                        <tr v-if="usuarios.data.length === 0">
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">No hay usuarios registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                <Pagination :meta="usuarios.meta" :links="usuarios.links" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { formatDateTime } from '@/helpers.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    usuarios: { type: Object, required: true },
});

const page = usePage();
const currentUser = page.props.auth?.user || page.props.user;

function confirmDelete(usuario) {
    if (usuario.id === currentUser.id) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No puedes eliminar tu propia cuenta.' });
        return;
    }

    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `Se eliminará "${usuario.name}" y su empleado asociado. Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/usuarios/${usuario.id}`, {
                onError: (errors) => {
                    const msg = Object.values(errors).flat().join(', ');
                    Swal.fire({ icon: 'error', title: 'Error', text: msg || 'No tienes permiso para eliminar este usuario.' });
                },
            });
        }
    });
}
</script>
