<template>
    <AppLayout title="Configuración">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Configuración de la empresa</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="update">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la empresa</label>
                            <input v-model="form.nombre" type="text" class="input w-full" :class="{ 'input-error': form.errors.nombre }" />
                            <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <input type="file" @input="form.logo = $event.target.files[0]" class="input w-full" :class="{ 'input-error': form.errors.logo }" />
                            <div v-if="form.errors.logo" class="text-red-500 text-xs mt-1">{{ form.errors.logo }}</div>
                            <img v-if="empresa.logo && !form.logo" :src="`/storage/${empresa.logo}`" class="mt-2 h-16 rounded-lg" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color primario</label>
                            <input v-model="form.color" type="color" class="input w-full h-10 p-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color secundario</label>
                            <input v-model="form.color_secundario" type="color" class="input w-full h-10 p-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipografía</label>
                            <select v-model="form.tipografia" class="input w-full">
                                <option value="inter">Inter</option>
                                <option value="roboto">Roboto</option>
                                <option value="poppins">Poppins</option>
                                <option value="montserrat">Montserrat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Moneda</label>
                            <select v-model="form.moneda" class="input w-full">
                                <option value="MXN">MXN - Peso Mexicano</option>
                                <option value="USD">USD - Dólar</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto</label>
                            <input v-model="form.telefono_contacto" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                            <input v-model="form.email_contacto" type="email" class="input w-full" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <textarea v-model="form.direccion" rows="3" class="input w-full"></textarea>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    empresa: { type: Object, required: true },
});

const form = useForm({
    nombre: props.empresa.nombre || '',
    logo: null,
    color: props.empresa.color || '#f59e0b',
    color_secundario: props.empresa.color_secundario || '',
    tipografia: props.empresa.tipografia || 'inter',
    moneda: props.empresa.moneda || 'MXN',
    telefono_contacto: props.empresa.telefono_contacto || '',
    email_contacto: props.empresa.email_contacto || '',
    direccion: props.empresa.direccion || '',
});

function update() {
    form.post('/configuracion', {
        _method: 'put',
        preserveScroll: true,
    });
}
</script>
