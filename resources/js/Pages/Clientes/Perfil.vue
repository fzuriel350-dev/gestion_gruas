<template>
    <AppLayout title="Mi Perfil">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-lg font-bold text-black"
                    style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
                    {{ (user?.name || '?')[0].toUpperCase() }}
                </div>
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
                                <input v-model="form.email" type="email" class="form-input" required />
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

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    password_confirmation: '',
    telefono: props.cliente?.telefono || '',
    direccion: props.cliente?.direccion || '',
    contacto: props.cliente?.contacto || '',
});

const submit = () => {
    form.post('/panel/perfil', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>
