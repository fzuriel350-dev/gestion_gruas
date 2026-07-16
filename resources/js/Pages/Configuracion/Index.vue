<template>
    <AppLayout title="Configuración">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Configuración de la empresa</h3>
            </div>
            <div class="card-body">
                <div v-if="$page.props.flash?.success" class="mb-4 p-3 rounded-lg bg-green-500/10 border border-green-500/30 text-sm text-green-400 font-medium">
                    {{ $page.props.flash.success }}
                </div>
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
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen de fondo (página de inicio / login)</label>
                            <input type="file" @input="form.imagen_fondo = $event.target.files[0]" class="input w-full" :class="{ 'input-error': form.errors.imagen_fondo }" />
                            <div v-if="form.errors.imagen_fondo" class="text-red-500 text-xs mt-1">{{ form.errors.imagen_fondo }}</div>
                            <img v-if="empresa.imagen_fondo && !form.imagen_fondo" :src="`/storage/${empresa.imagen_fondo}`" class="mt-2 h-32 w-full object-cover rounded-lg" />
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
                            <select v-model="form.tipografia" class="input w-full" :style="{ fontFamily: form.tipografia }" :class="{ 'input-error': form.errors.tipografia }">
                                <option v-for="font in fonts" :key="font" :value="font" :style="{ fontFamily: font }">{{ font }}</option>
                            </select>
                            <div v-if="form.errors.tipografia" class="text-red-500 text-xs mt-1">{{ form.errors.tipografia }}</div>
                            <p class="text-xs text-gray-500 mt-1">Vista previa: <span :style="{ fontFamily: form.tipografia }">ABCD abc123 — Grúas & Equipos</span></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Moneda</label>
                            <select v-model="form.moneda" class="input w-full">
                                <option value="MXN">MXN - Peso Mexicano</option>
                                <option value="USD">USD - Dolar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="$">$ - Peso</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto</label>
                            <input v-model="form.telefono_contacto" type="text" class="input w-full" :class="{ 'input-error': form.errors.telefono_contacto }" />
                            <div v-if="form.errors.telefono_contacto" class="text-red-500 text-xs mt-1">{{ form.errors.telefono_contacto }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                            <input v-model="form.email_contacto" type="text" class="input w-full" :class="{ 'input-error': form.errors.email_contacto }" />
                            <div v-if="form.errors.email_contacto" class="text-red-500 text-xs mt-1">{{ form.errors.email_contacto }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <textarea v-model="form.direccion" rows="3" class="input w-full" :class="{ 'input-error': form.errors.direccion }"></textarea>
                            <div v-if="form.errors.direccion" class="text-red-500 text-xs mt-1">{{ form.errors.direccion }}</div>
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

const fonts = [
    'Inter', 'Roboto', 'Poppins', 'Montserrat', 'Open Sans', 'Lato',
    'Oswald', 'Raleway', 'PT Sans', 'Source Sans 3', 'Nunito', 'Work Sans',
    'Quicksand', 'Rubik', 'Nunito Sans', 'DM Sans', 'Figtree',
    'Plus Jakarta Sans', 'Manrope', 'Outfit', 'Space Grotesk', 'Urbanist',
    'Red Hat Display', 'Sarabun',
];

const form = useForm({
    nombre: props.empresa.nombre || '',
    color: props.empresa.color || '#f59e0b',
    color_secundario: props.empresa.color_secundario || '',
    tipografia: props.empresa.tipografia || 'Inter',
    moneda: props.empresa.moneda || 'MXN',
    telefono_contacto: props.empresa.telefono_contacto || '',
    email_contacto: props.empresa.email_contacto || '',
    direccion: props.empresa.direccion || '',
    logo: null,
    imagen_fondo: null,
});

function update() {
    console.log('UPDATE EJECUTADO - logo:', form.logo, 'nombre:', form.nombre);
    form.post('/configuracion', {
        preserveScroll: true,
        forceFormData: true,
        onError: (errors) => console.log('ERRORES:', errors),
        onSuccess: () => console.log('EXITO'),
    });
}
</script>
