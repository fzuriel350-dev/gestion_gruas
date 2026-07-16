<template>
    <AppLayout title="Nuevo Cliente">
        <div class="card max-w-3xl mx-auto">
            <div class="card-header">
                <h3>Nuevo Cliente</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input v-model="form.nombre" type="text" class="form-input" required />
                            <p v-if="errors.nombre" class="form-error">{{ errors.nombre }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Empresa</label>
                            <input v-model="form.empresa" type="text" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contacto</label>
                            <input v-model="form.contacto" type="text" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input v-model="form.email" type="email" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aseguradora</label>
                            <select v-model="form.aseguradora_id" class="form-input">
                                <option :value="null">Seleccionar...</option>
                                <option v-for="a in aseguradoras" :key="a.id" :value="a.id">{{ a.nombre }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <textarea v-model="form.direccion" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                        <Link href="/clientes" class="btn btn-ghost">Cancelar</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    aseguradoras: { type: Array, default: () => [] },
});

const form = useForm({
    nombre: '',
    empresa: '',
    contacto: '',
    telefono: '',
    email: '',
    direccion: '',
    aseguradora_id: null,
});

const errors = {};

const submit = () => {
    form.post('/clientes');
};
</script>
