<template>
    <AppLayout title="Mi Perfil">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <img v-if="$page.props.empresa?.logo" :src="`/storage/${$page.props.empresa.logo}`" class="w-10 h-10 object-contain rounded-lg shrink-0">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900">{{ $page.props.empresa?.nombre || 'Grúas & Equipos' }}</h2>
                    <p class="text-xs text-gray-500">Cliente</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <label class="relative cursor-pointer group shrink-0">
                    <input type="file" accept="image/*" class="hidden" @change="onFotoChange">
                    <div v-if="fotoPreview || user?.foto_perfil_url"
                        class="w-14 h-14 rounded-xl overflow-hidden"
                        style="box-shadow:4px 4px 10px var(--geg-clay-shadow-dark),-4px -4px 10px var(--geg-clay-shadow-light);">
                        <img :src="fotoPreview || user?.foto_perfil_url" class="w-full h-full object-cover">
                    </div>
                    <div v-else class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-bold text-black"
                        style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
                        {{ (user?.name || '?')[0].toUpperCase() }}
                    </div>
                    <div class="absolute inset-0 rounded-xl bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </label>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ user?.name }}</h1>
                    <p class="text-sm text-gray-500">{{ user?.email }}</p>
                </div>
            </div>

            <div v-if="cliente" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ stats.total_cotizaciones }}</p>
                        <p class="text-xs text-gray-500 mt-1">Cotizaciones</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ stats.total_servicios }}</p>
                        <p class="text-xs text-gray-500 mt-1">Servicios totales</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ stats.servicios_activos }}</p>
                        <p class="text-xs text-gray-500 mt-1">Servicios activos</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Información personal</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input v-model="form.name" type="text" class="form-input" required />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input v-model="form.email" type="email" class="form-input" disabled />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="form-group" v-if="cliente">
                                <label class="form-label">Teléfono</label>
                                <input v-model="form.telefono" type="text" class="form-input" />
                                <InputError :message="form.errors.telefono" />
                            </div>
                            <div class="form-group" v-if="cliente">
                                <label class="form-label">Contacto adicional</label>
                                <input v-model="form.contacto" type="text" class="form-input" />
                                <InputError :message="form.errors.contacto" />
                            </div>
                            <div class="form-group md:col-span-2" v-if="cliente">
                                <label class="form-label">Dirección</label>
                                <input v-model="form.direccion" type="text" class="form-input" />
                                <InputError :message="form.errors.direccion" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-5">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Cambiar contraseña</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="form-group">
                                    <label class="form-label">Nueva contraseña</label>
                                    <input v-model="form.password" type="password" class="form-input" autocomplete="new-password" />
                                    <InputError :message="form.errors.password" />
                                    <div v-if="form.password.length > 0" class="mt-2 space-y-1">
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
                                <div class="form-group">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input v-model="form.password_confirmation" type="password" class="form-input" autocomplete="new-password" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar perfil</button>
                        </div>
                    </form>
                </div>
            </div>

            <div v-if="cliente?.aseguradora" class="card">
                <div class="card-header">
                    <h3>Aseguradora</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Aseguradora</p>
                            <p class="text-sm font-semibold text-gray-900">{{ cliente.aseguradora.nombre }}</p>
                        </div>
                        <div v-if="cliente.numero_poliza">
                            <p class="text-xs text-gray-500">Número de póliza</p>
                            <p class="text-sm font-semibold text-gray-900">{{ cliente.numero_poliza }}</p>
                        </div>
                        <div v-if="cliente.tipo_cobertura_poliza">
                            <p class="text-xs text-gray-500">Tipo de cobertura</p>
                            <p class="text-sm font-semibold text-gray-900">{{ cliente.tipo_cobertura_poliza }}</p>
                        </div>
                        <div v-if="cliente.empresa">
                            <p class="text-xs text-gray-500">Empresa</p>
                            <p class="text-sm font-semibold text-gray-900">{{ cliente.empresa }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import { watch } from 'vue';

const page = usePage();

const props = defineProps({
    user: { type: Object, required: true },
    cliente: { type: Object, default: null },
    stats: { type: Object, default: () => ({}) },
});

const fotoPreview = ref(null);

function onFotoChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.foto_perfil = file;
    const reader = new FileReader();
    reader.onload = (ev) => { fotoPreview.value = ev.target.result; };
    reader.readAsDataURL(file);
}

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    password_confirmation: '',
    telefono: props.cliente?.telefono || '',
    direccion: props.cliente?.direccion || '',
    contacto: props.cliente?.contacto || '',
    foto_perfil: null,
});

const hasMinLength = computed(() => form.password.length >= 8);
const hasLowercase = computed(() => /[a-z]/.test(form.password));
const hasUppercase = computed(() => /[A-Z]/.test(form.password));
const hasNumber = computed(() => /[0-9]/.test(form.password));
const hasSymbol = computed(() => /[^a-zA-Z0-9]/.test(form.password));

const passwordRules = computed(() => [
    { label: 'Mínimo 8 caracteres', met: hasMinLength.value },
    { label: 'Letras minúsculas', met: hasLowercase.value },
    { label: 'Letras mayúsculas', met: hasUppercase.value },
    { label: 'Al menos 1 número', met: hasNumber.value },
    { label: 'Al menos 1 símbolo', met: hasSymbol.value },
]);

const submit = () => {
    form.post('/panel/perfil', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
            fotoPreview.value = null;
            form.foto_perfil = null;
        },
    });
};
</script>
