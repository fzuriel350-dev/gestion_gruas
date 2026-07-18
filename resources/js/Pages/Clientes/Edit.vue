<template>
    <AppLayout :title="`Editar: ${cliente.nombre}`">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <Link :href="`/clientes/${cliente.id}`" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </Link>
                <h1 class="text-xl font-bold text-gray-900">Editar Cliente</h1>
            </div>
            <div class="card">
                <div class="card-body">
                <form @submit.prevent="submit" novalidate class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="form-label">Nombre <span class="text-red-500">*</span></label>
                            <input v-model="form.nombre" type="text" class="form-input" :class="{ 'border-red-500': form.errors.nombre }" @input="validarCampo('nombre')" />
                            <p v-if="form.errors.nombre" class="form-error">{{ form.errors.nombre }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono <span class="text-red-500">*</span></label>
                            <input v-model="form.telefono" type="text" class="form-input" :class="{ 'border-red-500': form.errors.telefono }" />
                            <p v-if="form.errors.telefono" class="form-error">{{ form.errors.telefono }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-red-500">*</span></label>
                            <input v-model="form.email" type="email" class="form-input" :class="{ 'border-red-500': form.errors.email }" @input="validarCampo('email')" />
                            <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aseguradora <span class="text-red-500">*</span></label>
                            <select v-model="form.aseguradora_id" class="form-input" :class="{ 'border-red-500': form.errors.aseguradora_id }">
                                <option value="">Seleccionar aseguradora...</option>
                                <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                            </select>
                            <p v-if="form.errors.aseguradora_id" class="form-error">{{ form.errors.aseguradora_id }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Número de Póliza <span class="text-red-500">*</span></label>
                            <input v-model="form.numero_poliza" type="text" class="form-input" :class="{ 'border-red-500': form.errors.numero_poliza }" />
                            <p v-if="form.errors.numero_poliza" class="form-error">{{ form.errors.numero_poliza }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de Cobertura <span class="text-red-500">*</span></label>
                            <input v-model="form.tipo_cobertura_poliza" type="text" class="form-input" :class="{ 'border-red-500': form.errors.tipo_cobertura_poliza }" @input="validarCampo('tipo_cobertura_poliza')" />
                            <p v-if="form.errors.tipo_cobertura_poliza" class="form-error">{{ form.errors.tipo_cobertura_poliza }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-5">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Dirección</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Calle <span class="text-red-500">*</span></label>
                                <input v-model="form.calle" type="text" class="form-input" :class="{ 'border-red-500': form.errors.calle }" @input="validarCampo('calle')" />
                                <p v-if="form.errors.calle" class="form-error">{{ form.errors.calle }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Exterior</label>
                                <input v-model="form.num_exterior" type="text" class="form-input" :class="{ 'border-red-500': form.errors.num_exterior }" @input="validarCampo('num_exterior')" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Núm. Interior</label>
                                <input v-model="form.num_interior" type="text" class="form-input" :class="{ 'border-red-500': form.errors.num_interior }" @input="validarCampo('num_interior')" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Colonia <span class="text-red-500">*</span></label>
                                <input v-model="form.colonia" type="text" class="form-input" :class="{ 'border-red-500': form.errors.colonia }" @input="validarCampo('colonia')" />
                                <p v-if="form.errors.colonia" class="form-error">{{ form.errors.colonia }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código Postal <span class="text-red-500">*</span></label>
                                <input v-model="form.codigo_postal" type="text" class="form-input" :class="{ 'border-red-500': form.errors.codigo_postal }" @input="validarCampo('codigo_postal')" />
                                <p v-if="form.errors.codigo_postal" class="form-error">{{ form.errors.codigo_postal }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Localidad <span class="text-red-500">*</span></label>
                                <input v-model="form.localidad" type="text" class="form-input" :class="{ 'border-red-500': form.errors.localidad }" @input="validarCampo('localidad')" />
                                <p v-if="form.errors.localidad" class="form-error">{{ form.errors.localidad }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Municipio <span class="text-red-500">*</span></label>
                                <input v-model="form.municipio" type="text" class="form-input" :class="{ 'border-red-500': form.errors.municipio }" @input="validarCampo('municipio')" />
                                <p v-if="form.errors.municipio" class="form-error">{{ form.errors.municipio }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado <span class="text-red-500">*</span></label>
                                <input v-model="form.estado" type="text" class="form-input" :class="{ 'border-red-500': form.errors.estado }" @input="validarCampo('estado')" />
                                <p v-if="form.errors.estado" class="form-error">{{ form.errors.estado }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar</button>
                        <Link :href="`/clientes/${cliente.id}`" class="btn btn-ghost">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    cliente: { type: Object, required: true },
    aseguradoras: { type: Array, default: () => [] },
});

const form = useForm({
    nombre: props.cliente.nombre || '',
    telefono: props.cliente.telefono || '',
    email: props.cliente.email || '',
    aseguradora_id: props.cliente.aseguradora_id || '',
    numero_poliza: props.cliente.numero_poliza || '',
    tipo_cobertura_poliza: props.cliente.tipo_cobertura_poliza || '',
    calle: props.cliente.calle || '',
    num_exterior: props.cliente.num_exterior || '',
    num_interior: props.cliente.num_interior || '',
    colonia: props.cliente.colonia || '',
    codigo_postal: props.cliente.codigo_postal || '',
    localidad: props.cliente.localidad || '',
    municipio: props.cliente.municipio || '',
    estado: props.cliente.estado || '',
});

const fieldValidators = {
    nombre: (v) => v && !/^[A-Z\u00C0-\u00D6\u00D8-\u00DE\u00F1\u00D1]/.test(v) ? 'El nombre debe comenzar con mayúscula.' : '',
    email: (v) => v && !v.includes('@') ? 'El correo debe contener un @.' : '',
    codigo_postal: (v) => v && !/^\d+$/.test(v) ? 'El código postal solo puede contener números.' : '',
    num_exterior: (v) => v && !/^[A-Za-z0-9\s]*$/.test(v) ? 'Solo letras y números.' : '',
    num_interior: (v) => v && !/^[A-Za-z0-9\s]*$/.test(v) ? 'Solo letras y números.' : '',
    calle: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
    colonia: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
    localidad: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
    municipio: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
    estado: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
    tipo_cobertura_poliza: (v) => v && !/^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/.test(v) ? 'Solo letras y espacios.' : '',
};

function validarCampo(key) {
    const fn = fieldValidators[key];
    if (!fn) return;
    const msg = fn(form[key]);
    if (msg) {
        form.setError(key, msg);
    } else {
        form.errors[key] = undefined;
    }
}

const requiredFields = [
    { key: 'nombre', label: 'Nombre' },
    { key: 'telefono', label: 'Teléfono' },
    { key: 'email', label: 'Email' },
    { key: 'aseguradora_id', label: 'Aseguradora' },
    { key: 'numero_poliza', label: 'Número de Póliza' },
    { key: 'tipo_cobertura_poliza', label: 'Tipo de Cobertura' },
    { key: 'calle', label: 'Calle' },
    { key: 'colonia', label: 'Colonia' },
    { key: 'codigo_postal', label: 'Código Postal' },
    { key: 'localidad', label: 'Localidad' },
    { key: 'municipio', label: 'Municipio' },
    { key: 'estado', label: 'Estado' },
];

function validar() {
    form.clearErrors();
    const vacios = [];
    const invalidos = [];

    for (const field of requiredFields) {
        if (!form[field.key] || (typeof form[field.key] === 'string' && !form[field.key].trim())) {
            form.setError(field.key, `El campo "${field.label}" es obligatorio.`);
            vacios.push(field.label);
        }
    }

    if (form.nombre && !/^[A-Z\u00C0-\u00D6\u00D8-\u00DE\u00F1\u00D1]/.test(form.nombre)) {
        form.setError('nombre', 'El nombre debe comenzar con mayúscula.');
        invalidos.push('Nombre (debe iniciar con mayúscula)');
    }

    if (form.email && !form.email.includes('@')) {
        form.setError('email', 'El correo debe contener un @.');
        invalidos.push('Email (falta @)');
    }

    if (form.codigo_postal && !/^\d+$/.test(form.codigo_postal)) {
        form.setError('codigo_postal', 'El código postal solo puede contener números.');
        invalidos.push('Código Postal (solo números)');
    }

    const soloAlfanumerico = /^[A-Za-z0-9\s]*$/;
    for (const campo of ['num_exterior', 'num_interior']) {
        if (form[campo] && !soloAlfanumerico.test(form[campo])) {
            const etiquetas = { num_exterior: 'Núm. Exterior', num_interior: 'Núm. Interior' };
            form.setError(campo, `${etiquetas[campo]} solo puede contener letras y números.`);
            invalidos.push(`${etiquetas[campo]} (solo letras/números)`);
        }
    }

    const soloLetras = /^[A-Za-z\u00C0-\u024F\u00F1\u00D1\s]+$/;
    for (const campo of ['calle', 'colonia', 'localidad', 'municipio', 'estado', 'tipo_cobertura_poliza']) {
        if (form[campo] && !soloLetras.test(form[campo])) {
            const etiquetas = { calle: 'Calle', colonia: 'Colonia', localidad: 'Localidad', municipio: 'Municipio', estado: 'Estado', tipo_cobertura_poliza: 'Tipo de Cobertura' };
            form.setError(campo, `${etiquetas[campo]} solo puede contener letras y espacios.`);
            invalidos.push(`${etiquetas[campo]} (solo letras)`);
        }
    }

    if (vacios.length > 0 || invalidos.length > 0) {
        let texto = '';
        if (vacios.length > 0) texto += `Campos vacíos: ${vacios.join(', ')}.`;
        if (invalidos.length > 0) texto += ` Campos inválidos: ${invalidos.join(', ')}.`;
        Swal.fire({
            icon: 'error',
            title: 'Revisa el formulario',
            text: texto,
            timer: 4000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
        return false;
    }

    return true;
}

const submit = () => {
    if (!validar()) return;
    form.patch(`/clientes/${props.cliente.id}`);
};
</script>