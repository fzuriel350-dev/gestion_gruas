<template>
    <AppLayout title="Editar Cotización">
        <div class="flex items-center gap-4 mb-5">
            <Link :href="`/cotizaciones/${cotizacione.id}`" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Editar Cotización</h1>
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
                            <select v-model="form.aseguradora_id" class="input">
                                <option value="">Sin aseguradora</option>
                                <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.aseguradora_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de servicio</label>
                            <select v-model="form.tipo_servicio_id" class="input" required>
                                <option value="">Seleccionar tipo</option>
                                <option v-for="t in tiposServicio" :key="t.id" :value="t.id">{{ t.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.tipo_servicio_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Convenio</label>
                            <select v-model="form.convenio_id" class="input">
                                <option value="">Sin convenio</option>
                                <option v-for="cv in convenios" :key="cv.id" :value="cv.id">{{ cv.nombre || cv.codigo }}</option>
                            </select>
                            <InputError :message="form.errors.convenio_id" />
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Dirección de origen</label>
                        <input type="text" v-model="form.origen_direccion" class="input" placeholder="Calle, número, colonia, ciudad, estado" required>
                        <InputError :message="form.errors.origen_direccion" />
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Dirección de destino</label>
                        <input type="text" v-model="form.destino_direccion" class="input" placeholder="Calle, número, colonia, ciudad, estado" required>
                        <InputError :message="form.errors.destino_direccion" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <Link :href="`/cotizaciones/${cotizacione.id}`" class="btn text-gray-600 hover:text-gray-800">Cancelar</Link>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar cotización</button>
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

const props = defineProps({
    cotizacione: { type: Object, required: true },
    clientes: { type: Array, default: () => [] },
    aseguradoras: { type: Array, default: () => [] },
    tiposServicio: { type: Array, default: () => [] },
    convenios: { type: Array, default: () => [] },
});

const form = useForm({
    cliente_id: props.cotizacione.cliente_id || '',
    aseguradora_id: props.cotizacione.aseguradora_id || '',
    tipo_servicio_id: props.cotizacione.tipo_servicio_id || '',
    convenio_id: props.cotizacione.convenio_id || '',
    origen_direccion: props.cotizacione.origen_direccion || '',
    destino_direccion: props.cotizacione.destino_direccion || '',
});

function submit() {
    form.put(`/cotizaciones/${props.cotizacione.id}`);
}
</script>
