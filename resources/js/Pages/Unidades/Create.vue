<template>
    <AppLayout title="Nueva Unidad">
        <div class="card max-w-2xl mx-auto">
            <div class="card-header">
                <h3>Nueva Unidad</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="store">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                            <input v-model="form.marca" type="text" class="input w-full" :class="{ 'input-error': form.errors.marca }" />
                            <div v-if="form.errors.marca" class="text-red-500 text-xs mt-1">{{ form.errors.marca }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <input v-model="form.tipo" type="text" class="input w-full" :class="{ 'input-error': form.errors.tipo }" />
                            <div v-if="form.errors.tipo" class="text-red-500 text-xs mt-1">{{ form.errors.tipo }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                            <input v-model="form.modelo" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                            <input v-model="form.año" type="number" class="input w-full" :class="{ 'input-error': form.errors.año }" />
                            <div v-if="form.errors.año" class="text-red-500 text-xs mt-1">{{ form.errors.año }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placas</label>
                            <input v-model="form.placas" type="text" class="input w-full" :class="{ 'input-error': form.errors.placas }" />
                            <div v-if="form.errors.placas" class="text-red-500 text-xs mt-1">{{ form.errors.placas }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número Económico</label>
                            <input v-model="form.numero_economico" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Serie</label>
                            <input v-model="form.numero_serie" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado Emplacado</label>
                            <input v-model="form.estado_emplacado" type="text" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Seguro Vencimiento</label>
                            <input v-model="form.seguro_vencimiento" type="date" class="input w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Oficina</label>
                            <select v-model="form.oficina_id" class="input w-full">
                                <option :value="null">Sin oficina</option>
                                <option v-for="oficina in oficinas" :key="oficina.id" :value="oficina.id">{{ oficina.nombre }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Operador</label>
                            <select v-model="form.operador_id" class="input w-full">
                                <option :value="null">Sin operador</option>
                                <option v-for="op in operadores" :key="op.id" :value="op.id">{{ op.empleado?.nombre }} {{ op.empleado?.apellido_paterno }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center gap-3 mt-6">
                                <input v-model="form.activo" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600" />
                                <span class="text-sm font-medium text-gray-700">Activo</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/unidades" class="btn btn-secondary">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    operadores: { type: Array, default: () => [] },
    oficinas: { type: Array, default: () => [] },
});

const form = useForm({
    marca: '',
    tipo: '',
    modelo: '',
    año: '',
    placas: '',
    numero_economico: '',
    numero_serie: '',
    estado_emplacado: '',
    seguro_vencimiento: '',
    oficina_id: null,
    operador_id: null,
    activo: true,
});

function store() {
    form.post('/unidades');
}
</script>
