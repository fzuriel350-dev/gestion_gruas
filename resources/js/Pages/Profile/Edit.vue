<template>
    <AppLayout title="Mi Perfil">
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-5 mb-6">
                        <label class="relative cursor-pointer group shrink-0">
                            <input type="file" accept="image/*" class="hidden" @change="onFotoChange">
                            <div v-if="fotoPreview || user?.foto_perfil_url"
                                class="w-20 h-20 rounded-2xl overflow-hidden"
                                style="box-shadow:4px 4px 10px var(--geg-clay-shadow-dark),-4px -4px 10px var(--geg-clay-shadow-light);">
                                <img :src="fotoPreview || user?.foto_perfil_url" class="w-full h-full object-cover">
                            </div>
                            <div v-else
                                class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-bold"
                                style="background:linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));color:black;box-shadow:4px 4px 10px var(--geg-clay-shadow-dark),-4px -4px 10px var(--geg-clay-shadow-light);">
                                {{ (user?.name || '?')[0].toUpperCase() }}
                            </div>
                            <div class="absolute inset-0 rounded-2xl bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </label>
                        <div>
                            <h2 class="text-lg font-bold">{{ user?.name }}</h2>
                            <p class="text-xs text-gray-500">Haz clic en la foto para cambiarla</p>
                            <div v-if="profileForm.errors.foto_perfil" class="text-red-500 text-xs mt-1">{{ profileForm.errors.foto_perfil }}</div>
                        </div>
                    </div>

                    <form @submit.prevent="updateProfile">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                <input v-model="profileForm.name" type="text" class="input w-full" :class="{ 'input-error': profileForm.errors.name }" />
                                <div v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input v-model="profileForm.email" type="email" class="input w-full" :class="{ 'input-error': profileForm.errors.email }" :disabled="user?.role === 'admin' || user?.role === 'cotizador' || user?.role === 'operador'" />
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
                                <input v-model="passwordForm.password" type="password" class="input w-full" :class="{ 'input-error': passwordForm.errors.password }" @input="passwordForm.errors.password = null" />
                                <div v-if="passwordForm.errors.password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password }}</div>
                                <div v-if="passwordForm.password.length > 0" class="mt-2 space-y-1">
                                    <div v-for="rule in passwordRules" :key="rule.label" class="flex items-center gap-2 text-xs">
                                        <div class="w-3.5 h-3.5 rounded-full flex items-center justify-center shrink-0"
                                            :class="rule.met ? 'bg-green-500' : 'bg-gray-200'">
                                            <svg v-if="rule.met" class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span :class="rule.met ? 'text-green-600' : 'text-gray-400'">{{ rule.label }}</span>
                                    </div>
                                </div>
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

            <div v-if="user?.role !== 'admin' && user?.role !== 'cotizador' && user?.role !== 'operador'" class="card border-red-200">
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
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || page.props.user);

const fotoPreview = ref(null);

function onFotoChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    profileForm.foto_perfil = file;
    const reader = new FileReader();
    reader.onload = (ev) => { fotoPreview.value = ev.target.result; };
    reader.readAsDataURL(file);
}

const profileForm = useForm({
    name: user.value?.name || '',
    email: user.value?.email || '',
    foto_perfil: null,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const hasMinLength = computed(() => passwordForm.password.length >= 8);
const hasLowercase = computed(() => /[a-z]/.test(passwordForm.password));
const hasUppercase = computed(() => /[A-Z]/.test(passwordForm.password));
const hasNumber = computed(() => /[0-9]/.test(passwordForm.password));
const hasSymbol = computed(() => /[^a-zA-Z0-9]/.test(passwordForm.password));

const passwordRules = computed(() => [
    { label: 'Mínimo 8 caracteres', met: hasMinLength.value },
    { label: 'Letras minúsculas', met: hasLowercase.value },
    { label: 'Letras mayúsculas', met: hasUppercase.value },
    { label: 'Al menos 1 número', met: hasNumber.value },
    { label: 'Al menos 1 símbolo', met: hasSymbol.value },
]);

const deleteForm = useForm({
    password: '',
});

function updateProfile() {
    profileForm.post('/profile', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            fotoPreview.value = null;
            profileForm.foto_perfil = null;
        },
    });
}

function updatePassword() {
    passwordForm.put('/password');
}

function deleteAccount() {
    deleteForm.delete('/profile');
}
</script>
