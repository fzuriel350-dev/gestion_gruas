<template>
    <AppLayout title="Nueva Cotización">
        <div class="flex items-center gap-4 mb-5">
            <Link href="/cotizaciones" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Nueva Cotización</h1>
        </div>

        <div class="card max-w-3xl">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Cliente</label>
                            <select v-model="form.cliente_id" class="input" required>
                                <option value="">Seleccionar cliente</option>
                                <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre || c.name }}</option>
                            </select>
                            <InputError :message="form.errors.cliente_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aseguradora</label>
                            <select v-model="form.aseguradora_id" class="input" :disabled="!!clienteSeleccionado?.aseguradora_id">
                                <option value="">Sin aseguradora</option>
                                <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                            </select>
                            <p v-if="clienteSeleccionado?.aseguradora" class="text-xs text-gray-500 mt-1">Aseguradora del cliente: <strong>{{ clienteSeleccionado.aseguradora.nombre }}</strong></p>
                            <InputError :message="form.errors.aseguradora_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de servicio</label>
                            <select v-model="form.tipo_servicio_id" class="input" required>
                                <option value="">Seleccionar tipo</option>
                                <option v-for="t in tiposFiltrados" :key="t.id" :value="t.id">{{ t.nombre }}</option>
                            </select>
                            <p v-if="form.aseguradora_id && !tiposFiltrados.length" class="text-xs text-amber-600 mt-1">Esta aseguradora no tiene tipos de servicio asignados</p>
                            <InputError :message="form.errors.tipo_servicio_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Convenio</label>
                            <select v-model="form.convenio_aplicado_id" class="input">
                                <option value="">Sin convenio</option>
                                <option v-for="cv in convenios" :key="cv.id" :value="cv.id">{{ cv.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.convenio_aplicado_id" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5 pt-5 border-t border-gray-200">
                        <div class="form-group">
                            <label class="form-label">Distancia (km) <span class="text-red-500">*</span></label>
                            <input v-model="form.distancia_km" type="number" step="0.1" class="input" required />
                            <InputError :message="form.errors.distancia_km" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tiempo estimado (min) <span class="text-red-500">*</span></label>
                            <input v-model="form.tiempo_estimado_minutos" type="number" class="input" required />
                            <InputError :message="form.errors.tiempo_estimado_minutos" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Costo banderazo <span class="text-red-500">*</span></label>
                            <input v-model="form.costo_banderazo" type="number" step="0.01" class="input" required />
                            <InputError :message="form.errors.costo_banderazo" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Costo por km <span class="text-red-500">*</span></label>
                            <input v-model="form.costo_km" type="number" step="0.01" class="input" required />
                            <InputError :message="form.errors.costo_km" />
                        </div>
                    </div>

                    <div class="mt-5 pt-5 border-t border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="form.incluye_peajes" type="checkbox" class="sr-only peer" />
                                <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                            <label class="text-sm font-medium text-gray-700 cursor-pointer select-none">Incluir peajes / casetas</label>
                        </div>
                        <div v-if="form.incluye_peajes" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Número de casetas</label>
                                <input v-model="form.num_casetas" type="number" min="0" class="input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Costo aproximado de casetas</label>
                                <input v-model="form.costo_aprox_casetas" type="number" step="0.01" min="0" class="input" />
                            </div>
                            <p v-if="convenioSeleccionado?.cubre_casetas_peaje" class="text-xs text-green-600 col-span-full">Este convenio cubre casetas</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-5 mt-5">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Dirección de origen</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Calle <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_calle" type="text" class="input" required />
                                <InputError :message="form.errors.origen_calle" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Exterior</label>
                                <input v-model="form.origen_num_exterior" type="text" class="input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Interior</label>
                                <input v-model="form.origen_num_interior" type="text" class="input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Colonia <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_colonia" type="text" class="input" required />
                                <InputError :message="form.errors.origen_colonia" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código Postal <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_codigo_postal" type="text" class="input" required pattern="[0-9]*" />
                                <InputError :message="form.errors.origen_codigo_postal" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Localidad <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_localidad" type="text" class="input" required />
                                <InputError :message="form.errors.origen_localidad" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Municipio <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_municipio" type="text" class="input" required />
                                <InputError :message="form.errors.origen_municipio" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado <span class="text-red-500">*</span></label>
                                <input v-model="form.origen_estado" type="text" class="input" required />
                                <InputError :message="form.errors.origen_estado" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-5 mt-5">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Dirección de destino</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Calle <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_calle" type="text" class="input" required />
                                <InputError :message="form.errors.destino_calle" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Exterior</label>
                                <input v-model="form.destino_num_exterior" type="text" class="input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Interior</label>
                                <input v-model="form.destino_num_interior" type="text" class="input" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Colonia <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_colonia" type="text" class="input" required />
                                <InputError :message="form.errors.destino_colonia" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código Postal <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_codigo_postal" type="text" class="input" required pattern="[0-9]*" />
                                <InputError :message="form.errors.destino_codigo_postal" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Localidad <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_localidad" type="text" class="input" required />
                                <InputError :message="form.errors.destino_localidad" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Municipio <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_municipio" type="text" class="input" required />
                                <InputError :message="form.errors.destino_municipio" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado <span class="text-red-500">*</span></label>
                                <input v-model="form.destino_estado" type="text" class="input" required />
                                <InputError :message="form.errors.destino_estado" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <Link href="/cotizaciones" class="btn text-gray-600 hover:text-gray-800">Cancelar</Link>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar cotización</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';

import { computed, watch } from 'vue';

const props = defineProps({
    clientes: { type: Array, default: () => [] },
    aseguradoras: { type: Array, default: () => [] },
    tiposServicio: { type: Array, default: () => [] },
    convenios: { type: Array, default: () => [] },
});

const form = useForm({
    cliente_id: '',
    aseguradora_id: '',
    tipo_servicio_id: '',
    convenio_aplicado_id: '',
    distancia_km: '',
    tiempo_estimado_minutos: '',
    costo_banderazo: '',
    costo_km: '',
    incluye_peajes: false,
    num_casetas: 0,
    costo_aprox_casetas: 0,
    origen_calle: '',
    origen_num_exterior: '',
    origen_num_interior: '',
    origen_colonia: '',
    origen_codigo_postal: '',
    origen_localidad: '',
    origen_municipio: '',
    origen_estado: '',
    destino_calle: '',
    destino_num_exterior: '',
    destino_num_interior: '',
    destino_colonia: '',
    destino_codigo_postal: '',
    destino_localidad: '',
    destino_municipio: '',
    destino_estado: '',
});

const clienteSeleccionado = computed(() => {
    return props.clientes.find(c => c.id === form.cliente_id);
});

const convenioSeleccionado = computed(() => {
    return props.convenios.find(c => c.id === form.convenio_aplicado_id);
});

const mostrarCasetas = computed(() => {
    return convenioSeleccionado.value?.cubre_casetas_peaje;
});

watch(() => form.cliente_id, (nuevoId) => {
    if (!nuevoId) return;
    const cliente = props.clientes.find(c => c.id == nuevoId);
    form.aseguradora_id = cliente?.aseguradora_id || '';
    form.tipo_servicio_id = '';
    form.convenio_aplicado_id = '';
});

watch(() => form.aseguradora_id, (nuevoId) => {
    form.tipo_servicio_id = '';
    form.convenio_aplicado_id = '';

    if (nuevoId) {
        const primerConvenio = props.convenios.find(c => c.aseguradora_id == nuevoId);
        if (primerConvenio) {
            if (primerConvenio.costo_banderazo) form.costo_banderazo = primerConvenio.costo_banderazo;
            if (primerConvenio.costo_km) form.costo_km = primerConvenio.costo_km;
        }
    }
});

watch(() => form.convenio_aplicado_id, (nuevoId) => {
    if (!nuevoId) return;
    const convenio = props.convenios.find(c => c.id === nuevoId);
    if (convenio) {
        if (convenio.costo_banderazo) form.costo_banderazo = convenio.costo_banderazo;
        if (convenio.costo_km) form.costo_km = convenio.costo_km;
        if (convenio.cubre_casetas_peaje) {
            form.incluye_peajes = true;
        }
    }
});

const tiposFiltrados = computed(() => {
    if (!form.aseguradora_id) return props.tiposServicio;
    const aseguradora = props.aseguradoras.find(a => a.id === form.aseguradora_id);
    if (!aseguradora?.tipos_servicio?.length) return [];
    const ids = aseguradora.tipos_servicio.map(t => t.id);
    return props.tiposServicio.filter(t => ids.includes(t.id));
});

function submit() {
    form.post('/cotizaciones');
}
</script>
