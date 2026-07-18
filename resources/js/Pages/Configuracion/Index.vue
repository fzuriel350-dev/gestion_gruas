<template>
    <AppLayout title="Configuración">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3>Información de la empresa</h3>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slogan</label>
                                <input v-model="form.slogan" type="text" class="input w-full" placeholder="Ej: Confianza en el camino" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                                <input type="file" @input="form.logo = $event.target.files[0]" class="input w-full" :class="{ 'input-error': form.errors.logo }" />
                                <div v-if="form.errors.logo" class="text-red-500 text-xs mt-1">{{ form.errors.logo }}</div>
                                <div v-if="$page.props.empresa?.logo && !form.logo" class="mt-2 p-4 rounded-lg inline-flex" style="background:#f0f0f0;">
                                    <img :src="$page.props.empresa.logo" style="height:80px;width:auto;max-width:300px;object-fit:contain;" />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen de fondo (página de inicio / login)</label>
                                <input type="file" @input="form.imagen_fondo = $event.target.files[0]" class="input w-full" :class="{ 'input-error': form.errors.imagen_fondo }" />
                                <div v-if="form.errors.imagen_fondo" class="text-red-500 text-xs mt-1">{{ form.errors.imagen_fondo }}</div>
                                <img v-if="$page.props.empresa?.imagen_fondo && !form.imagen_fondo" :src="$page.props.empresa.imagen_fondo" class="mt-2 h-32 w-full object-cover rounded-lg" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Sección: Nosotros</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update">
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">¿Quiénes somos?</label>
                                <textarea v-model="form.quienes_somos" rows="4" class="input w-full" placeholder="Describe tu empresa..."></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Misión</label>
                                    <textarea v-model="form.mision" rows="4" class="input w-full" placeholder="Misión de la empresa..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Visión</label>
                                    <textarea v-model="form.vision" rows="4" class="input w-full" placeholder="Visión de la empresa..."></textarea>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                                <textarea v-model="form.prioridad" rows="4" class="input w-full" placeholder="Describe la prioridad de la empresa..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valores (uno por línea)</label>
                                <textarea v-model="form.valores" rows="5" class="input w-full" placeholder="Responsabilidad&#10;Confianza&#10;Respeto"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Accesos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div v-for="(acceso, index) in accesosRapidos" :key="acceso.id || index"
                            class="flex flex-wrap items-end gap-3 p-4 rounded-lg" style="background:#f5f5f5;">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-xs text-gray-500 mb-1">Título</label>
                                <input v-model="acceso.titulo" type="text" class="input w-full" placeholder="Ej: Facebook" />
                            </div>
                            <div class="w-20">
                                <label class="block text-xs text-gray-500 mb-1">Ícono</label>
                                <input type="file" accept="image/*" @change="acceso.icono_file = $event.target.files[0]" class="text-xs" />
                                <img v-if="acceso.icono_imagen_url && !acceso.icono_file" :src="acceso.icono_imagen_url" class="mt-1 h-8 w-8 object-contain" />
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-xs text-gray-500 mb-1">URL</label>
                                <input v-model="acceso.url" type="text" class="input w-full" placeholder="https://facebook.com/..." />
                            </div>
                            <button @click="editarAcceso(acceso)" class="btn btn-sm btn-primary">Guardar</button>
                            <button @click="eliminarAcceso(acceso.id)" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;">Eliminar</button>
                        </div>
                        <div class="flex flex-wrap items-end gap-3 p-4 rounded-lg" style="background:#fafafa;border:2px dashed #ddd;">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-xs text-gray-500 mb-1">Título</label>
                                <input v-model="nuevoAcceso.titulo" type="text" class="input w-full" placeholder="Ej: Facebook" />
                            </div>
                            <div class="w-20">
                                <label class="block text-xs text-gray-500 mb-1">Ícono</label>
                                <input type="file" accept="image/*" @change="nuevoAcceso.icono_file = $event.target.files[0]" class="text-xs" />
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-xs text-gray-500 mb-1">URL</label>
                                <input v-model="nuevoAcceso.url" type="text" class="input w-full" placeholder="https://..." />
                            </div>
                            <button @click="agregarAcceso" class="btn btn-sm btn-primary">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Contacto</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto</label>
                                <input v-model="form.telefono_contacto" type="text" class="input w-full" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                                <input v-model="form.email_contacto" type="text" class="input w-full" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación / Dirección</label>
                                <textarea v-model="form.direccion" rows="3" class="input w-full"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Apariencia</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                                <select v-model="form.tipografia" class="input w-full" :style="{ fontFamily: form.tipografia }">
                                    <option v-for="font in fonts" :key="font" :value="font" :style="{ fontFamily: font }">{{ font }}</option>
                                </select>
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
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    empresa: { type: Object, required: true },
    accesosRapidos: { type: Array, default: () => [] },
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
    slogan: props.empresa.slogan || '',
    quienes_somos: props.empresa.quienes_somos || '',
    mision: props.empresa.mision || '',
    vision: props.empresa.vision || '',
    valores: Array.isArray(props.empresa.valores) ? props.empresa.valores.join('\n') : (props.empresa.valores || ''),
    prioridad: Array.isArray(props.empresa.prioridad) ? props.empresa.prioridad.join('\n') : (props.empresa.prioridad || ''),
    color: props.empresa.color || '#FFD500',
    color_secundario: props.empresa.color_secundario || '#E6A000',
    tipografia: props.empresa.tipografia || 'Inter',
    moneda: props.empresa.moneda || 'MXN',
    telefono_contacto: props.empresa.telefono_contacto || '',
    email_contacto: props.empresa.email_contacto || '',
    direccion: props.empresa.direccion || '',
    logo: null,
    imagen_fondo: null,
});

const accesosRapidos = ref(props.accesosRapidos || []);
const nuevoAcceso = ref({ titulo: '', icono_file: null, url: '' });

function recargar() {
    router.visit('/configuracion', { preserveScroll: true, preserveState: false });
}

function formDataFrom(obj) {
    const fd = new FormData();
    Object.keys(obj).forEach(key => {
        if (obj[key] !== null && obj[key] !== undefined) {
            fd.append(key, obj[key]);
        }
    });
    return fd;
}

function agregarAcceso() {
    if (!nuevoAcceso.value.titulo || !nuevoAcceso.value.url) return;
    const fd = formDataFrom({
        titulo: nuevoAcceso.value.titulo,
        icono_imagen: nuevoAcceso.value.icono_file,
        url: nuevoAcceso.value.url,
    });
    router.post('/accesos-rapidos', fd, {
        preserveScroll: true,
        onSuccess: () => { nuevoAcceso.value = { titulo: '', icono_file: null, url: '' }; },
    });
}

function editarAcceso(acceso) {
    const data = { titulo: acceso.titulo, url: acceso.url };
    if (acceso.icono_file) {
        const fd = new FormData();
        fd.append('titulo', acceso.titulo);
        fd.append('url', acceso.url);
        fd.append('icono_imagen', acceso.icono_file);
        fd.append('_method', 'PUT');
        router.post(`/accesos-rapidos/${acceso.id}`, fd, { preserveScroll: true });
    } else {
        router.put(`/accesos-rapidos/${acceso.id}`, data, { preserveScroll: true });
    }
}

function eliminarAcceso(id) {
    if (!confirm('¿Eliminar este acceso rápido?')) return;
    router.delete(`/accesos-rapidos/${id}`, { preserveScroll: true });
}

function update() {
    form.post('/configuracion', {
        preserveScroll: true,
    });
}

</script>
