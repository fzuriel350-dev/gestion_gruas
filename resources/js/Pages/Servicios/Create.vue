<template>
    <AppLayout title="Nuevo Servicio">
        <div class="flex items-center gap-4 mb-5">
            <Link href="/servicios" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Nuevo Servicio</h1>
        </div>

        <div class="card max-w-3xl">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Cotización</label>
                            <select v-model="form.cotizacion_id" class="input">
                                <option value="">Seleccionar cotización (opcional)</option>
                                <option v-for="c in cotizaciones" :key="c.id" :value="c.id">{{ c.folio }} - {{ c.cliente?.nombre || c.cliente?.name }}</option>
                            </select>
                            <InputError :message="form.errors.cotizacion_id" />
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
                            <label class="form-label">Operador</label>
                            <select v-model="form.operador_id" class="input">
                                <option value="">Seleccionar operador</option>
                                <option v-for="o in operadores" :key="o.id" :value="o.id">{{ o.nombre || o.name }}</option>
                            </select>
                            <InputError :message="form.errors.operador_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Unidad</label>
                            <select v-model="form.unidad_id" class="input">
                                <option value="">Seleccionar unidad</option>
                                <option v-for="u in unidades" :key="u.id" :value="u.id">{{ u.nombre || u.placa || u.numero_economico }}</option>
                            </select>
                            <InputError :message="form.errors.unidad_id" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Oficina</label>
                            <select v-model="form.oficina_id" class="input">
                                <option value="">Seleccionar oficina</option>
                                <option v-for="of in oficinas" :key="of.id" :value="of.id">{{ of.nombre }}</option>
                            </select>
                            <InputError :message="form.errors.oficina_id" />
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="form-label">Descripción</label>
                        <textarea v-model="form.descripcion" class="input" rows="3" placeholder="Detalles del servicio"></textarea>
                        <InputError :message="form.errors.descripcion" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <Link href="/servicios" class="btn text-gray-600 hover:text-gray-800">Cancelar</Link>
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar servicio</button>
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
    cotizaciones: { type: Array, default: () => [] },
    operadores: { type: Array, default: () => [] },
    unidades: { type: Array, default: () => [] },
    tiposServicio: { type: Array, default: () => [] },
    oficinas: { type: Array, default: () => [] },
});

const form = useForm({
    cotizacion_id: '',
    operador_id: '',
    unidad_id: '',
    tipo_servicio_id: '',
    oficina_id: '',
    descripcion: '',
});

function submit() {
    form.post('/servicios');
}
</script>
