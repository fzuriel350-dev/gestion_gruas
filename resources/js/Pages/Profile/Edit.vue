<template>
    <AppLayout title="Mi Perfil">
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3>Información del perfil</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="updateProfile">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                <input v-model="profileForm.name" type="text" class="input w-full" :class="{ 'input-error': profileForm.errors.name }" />
                                <div v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input v-model="profileForm.email" type="email" class="input w-full" :class="{ 'input-error': profileForm.errors.email }" />
                                <div v-if="profileForm.errors.email" class="text-red-500 text-xs mt-1">{{ profileForm.errors.email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="profileForm.processing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Actualizar contraseña</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="updatePassword">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                                <input v-model="passwordForm.current_password" type="password" class="input w-full" :class="{ 'input-error': passwordForm.errors.current_password }" />
                                <div v-if="passwordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.current_password }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                                <input v-model="passwordForm.password" type="password" class="input w-full" :class="{ 'input-error': passwordForm.errors.password }" />
                                <div v-if="passwordForm.errors.password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                                <input v-model="passwordForm.password_confirmation" type="password" class="input w-full" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="passwordForm.processing">Actualizar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-red-200">
                <div class="card-header">
                    <h3 class="text-red-600">Eliminar cuenta</h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-gray-600 mb-4">Una vez que elimines tu cuenta, no podrás recuperarla. Todos tus datos serán eliminados permanentemente.</p>
                    <form @submit.prevent="deleteAccount">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ingresa tu contraseña para confirmar</label>
                                <input v-model="deleteForm.password" type="password" class="input w-full" :class="{ 'input-error': deleteForm.errors.password }" />
                                <div v-if="deleteForm.errors.password" class="text-red-500 text-xs mt-1">{{ deleteForm.errors.password }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700" :disabled="deleteForm.processing">Eliminar cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const user = usePage().props.auth?.user || usePage().props.user;

const profileForm = useForm({
    name: user?.name || '',
    email: user?.email || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const deleteForm = useForm({
    password: '',
});

function updateProfile() {
    profileForm.patch('/profile');
}

function updatePassword() {
    passwordForm.put('/password');
}

function deleteAccount() {
    deleteForm.delete('/profile');
}
</script>
