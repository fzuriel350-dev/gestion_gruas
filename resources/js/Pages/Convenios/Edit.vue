<template>
    <AppLayout title="Editar Convenio">
        <div class="mb-5">
            <Link :href="`/convenios/${convenio.id}`" class="text-sm font-semibold" :style="{ color: 'var(--geg-yellow-dark)' }">&larr; Volver</Link>
        </div>

        <div class="card max-w-2xl">
            <div class="card-header">
                <h3>Editar Convenio</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label class="form-label">Aseguradora <span class="text-red-500">*</span></label>
                        <select v-model="form.aseguradora_id" class="input" :class="{ 'border-red-400': form.errors.aseguradora_id }" @change="onAseguradoraChange">
                            <option value="">Seleccionar aseguradora</option>
                            <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                        </select>
                        <div v-if="form.errors.aseguradora_id" class="text-xs text-red-500 mt-1">{{ form.errors.aseguradora_id }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipo de Servicio <span class="text-red-500">*</span></label>
                        <select v-model="form.tipo_servicio_id" class="input" :class="{ 'border-red-400': form.errors.tipo_servicio_id }">
                            <option value="">Seleccionar tipo</option>
                            <option v-for="t in tiposServicio" :key="t.id" :value="t.id">{{ t.nombre }}</option>
                        </select>
                        <div v-if="form.errors.tipo_servicio_id" class="text-xs text-red-500 mt-1">{{ form.errors.tipo_servicio_id }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nombre del Convenio <span class="text-red-500">*</span></label>
                        <input type="text" v-model="form.nombre" class="input" :class="{ 'border-red-400': form.errors.nombre }">
                        <div v-if="form.errors.nombre" class="text-xs text-red-500 mt-1">{{ form.errors.nombre }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipo <span class="text-red-500">*</span></label>
                        <select v-model="form.tipo" class="input" :class="{ 'border-red-400': form.errors.tipo }">
                            <option value="">Seleccionar tipo</option>
                            <option value="local">Local</option>
                            <option value="foraneo">Foráneo</option>
                        </select>
                        <div v-if="form.errors.tipo" class="text-xs text-red-500 mt-1">{{ form.errors.tipo }}</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Costo Banderazo <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" v-model="form.costo_banderazo" class="input" :class="{ 'border-red-400': form.errors.costo_banderazo }">
                            <div v-if="form.errors.costo_banderazo" class="text-xs text-red-500 mt-1">{{ form.errors.costo_banderazo }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Costo por Km <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" v-model="form.costo_km" class="input" :class="{ 'border-red-400': form.errors.costo_km }">
                            <div v-if="form.errors.costo_km" class="text-xs text-red-500 mt-1">{{ form.errors.costo_km }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label">Km Incluidos <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" v-model="form.km_incluidos" class="input" :class="{ 'border-red-400': form.errors.km_incluidos }">
                            <div v-if="form.errors.km_incluidos" class="text-xs text-red-500 mt-1">{{ form.errors.km_incluidos }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descuento (%) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" v-model="form.descuento" class="input" :class="{ 'border-red-400': form.errors.descuento }">
                            <div v-if="form.errors.descuento" class="text-xs text-red-500 mt-1">{{ form.errors.descuento }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cubre Casetas</label>
                            <select v-model="form.cubre_casetas_peaje" class="input" :class="{ 'border-red-400': form.errors.cubre_casetas_peaje }">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                            <div v-if="form.errors.cubre_casetas_peaje" class="text-xs text-red-500 mt-1">{{ form.errors.cubre_casetas_peaje }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cobertura <span class="text-red-500">*</span></label>
                        <input type="text" v-model="form.cobertura" class="input" :class="{ 'border-red-400': form.errors.cobertura }">
                        <div v-if="form.errors.cobertura" class="text-xs text-red-500 mt-1">{{ form.errors.cobertura }}</div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">
                            {{ form.processing ? 'Actualizando...' : 'Actualizar' }}
                        </button>
                        <Link :href="`/convenios/${convenio.id}`" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    convenio: { type: Object, required: true },
    aseguradoras: { type: Array, default: () => [] },
    tiposServicio: { type: Array, default: () => [] },
});

const form = useForm({
    aseguradora_id: props.convenio.aseguradora_id,
    tipo_servicio_id: props.convenio.tipo_servicio_id,
    nombre: props.convenio.nombre,
    tipo: props.convenio.tipo,
    costo_banderazo: props.convenio.costo_banderazo,
    costo_km: props.convenio.costo_km,
    km_incluidos: props.convenio.km_incluidos,
    cubre_casetas_peaje: String(props.convenio.cubre_casetas_peaje),
    descuento: props.convenio.descuento,
    cobertura: props.convenio.cobertura,
});

function onAseguradoraChange() {
    form.tipo_servicio_id = '';
}

function submit() {
    form.put(`/convenios/${props.convenio.id}`);
}
</script>
