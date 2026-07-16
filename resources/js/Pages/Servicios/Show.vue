<template>
    <AppLayout :title="'Servicio ' + servicio.folio">
        <div class="flex items-center gap-4 mb-5">
            <Link href="/servicios" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <h1 class="text-xl font-bold text-gray-900">Servicio {{ servicio.folio }}</h1>
            <div class="ml-auto flex gap-2">
                <Link :href="`/servicios/${servicio.id}/edit`" class="btn btn-primary">Editar</Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 space-y-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Progreso del servicio</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Progreso</span>
                        </div>
                        <div class="relative flex items-start justify-between px-1">
                            <template v-for="(step, i) in estados" :key="i">
                                <div class="flex flex-col items-center relative z-10" style="flex: 1;">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold transition-all duration-300 shrink-0"
                                        :style="circleStyle(i)">
                                        <svg v-if="i < progreso" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        <span v-else-if="i === progreso" class="text-white">{{ i + 1 }}</span>
                                        <span v-else class="text-gray-400">{{ i + 1 }}</span>
                                    </div>
                                    <span class="text-[10px] font-semibold text-center leading-tight mt-1.5 transition-all duration-200 max-w-[56px]"
                                        :class="i <= progreso ? 'text-gray-800' : 'text-gray-400'">{{ step }}</span>
                                </div>
                                <div v-if="i < estados.length - 1" class="relative top-[14px] z-0 h-0.5 transition-all duration-500 -mx-2"
                                    :style="{ flex: '0 1 auto', width: '100%', background: i < progreso ? progressColor : '#e5e7eb' }"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Información general</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Folio</label>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ servicio.folio }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Estado</label>
                            <p class="mt-0.5">
                                <span class="status" :class="'status-' + statusClass(servicio.estado)">
                                    <span class="status-dot"></span> {{ servicio.estado }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Cliente</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.cliente?.nombre || servicio.cliente?.name || servicio.cotizacion?.cliente?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tipo de servicio</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.tipo_servicio?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Oficina</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.oficina?.nombre || '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Descripción</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.descripcion || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Direcciones</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Origen</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.origen_direccion }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Destino</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ servicio.destino_direccion }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Historial</h3>
                    </div>
                    <div class="card-body grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Creada</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ formatDateTime(servicio.created_at) }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-medium uppercase tracking-wide">Actualizada</label>
                            <p class="text-sm text-gray-900 mt-0.5">{{ formatDateTime(servicio.updated_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Operador</h3>
                    </div>
                    <div class="card-body">
                        <div v-if="servicio.operador" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-black shrink-0" :style="{ background: 'var(--geg-yellow)' }">
                                {{ (servicio.operador.empleado?.nombre || '').substring(0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ servicio.operador.empleado?.nombre || 'Operador' }}</p>
                                <p class="text-xs text-gray-500">{{ servicio.operador.empleado?.telefono || '' }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-400">Sin operador asignado</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Unidad</h3>
                    </div>
                    <div class="card-body">
                        <div v-if="servicio.unidad" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-black shrink-0" style="background: #dbeafe; color: #1d4ed8;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ servicio.unidad.nombre || servicio.unidad.placa || servicio.unidad.numero_economico }}</p>
                                <p class="text-xs text-gray-500">{{ servicio.unidad.tipo || '' }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-400">Sin unidad asignada</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body space-y-2">
                        <button @click="avanzarEstado" class="btn btn-primary w-full text-center" v-if="servicio.estado !== 'pendiente' && servicio.estado !== 'finalizado' && servicio.estado !== 'cancelado'">
                            Avanzar estado
                        </button>
                        <button @click="asignarOperador" class="btn w-full text-center border border-gray-200 text-gray-700 hover:bg-gray-50" v-if="!servicio.operador_id">
                            Asignar operador
                        </button>
                        <button @click="cancelarServicio" class="btn w-full text-center text-red-600 border border-red-200 hover:bg-red-50" v-if="servicio.estado !== 'cancelado' && servicio.estado !== 'finalizado'">
                            Cancelar servicio
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDateTime } from '@/helpers.js';
import Swal from 'sweetalert2';

const props = defineProps({
    servicio: { type: Object, required: true },
    progreso: { type: Number, default: 0 },
    operadores: { type: Array, default: () => [] },
    unidades: { type: Array, default: () => [] },
});

const estados = ['Asignado', 'En ruta', 'En sitio', 'Cargando', 'En transporte', 'Finalizado'];

const progressColor = computed(() => {
    const p = props.progreso;
    if (p < 0) return '#d1d5db';
    if (p === 0) return '#9ca3af';
    if (p <= 2) return '#f59e0b';
    if (p <= 4) return '#3b82f6';
    return '#10b981';
});

function circleStyle(i) {
    const p = props.progreso;
    const color = progressColor.value;
    if (p < 0) return { background: '#fff', border: '2px solid #d1d5db', color: '#9ca3af' };
    if (i < p) return { background: color, border: '2px solid ' + color, color: '#fff' };
    if (i === p) return { background: color, border: '2px solid ' + color, color: '#fff', boxShadow: '0 0 0 3px ' + color + '33' };
    return { background: '#fff', border: '2px solid #d1d5db', color: '#9ca3af' };
}

const nextEstadoMap = {
    asignado: 'inicio_servicio',
    inicio_servicio: 'en_sitio_origen',
    en_sitio_origen: 'en_carga',
    en_carga: 'en_transito',
    en_transito: 'en_sitio_destino',
    en_sitio_destino: 'finalizado',
};

const optionalFieldsMap = {
    asignado: [{ label: 'Kilometraje de salida', name: 'kms_salida' }],
    inicio_servicio: [{ label: 'Kilometraje al llegar al cliente', name: 'kms_llegada_cliente' }],
    en_carga: [{ label: 'Kilometraje al terminar servicio', name: 'kms_termino_servicio' }],
    en_sitio_destino: [
        { label: 'Kilometraje de regreso a base', name: 'kms_regreso_base' },
        { label: 'Kilómetros a cobrar', name: 'kms_cobrados_reales' },
    ],
};

function avanzarEstado() {
    const estado = props.servicio.estado;

    if (estado === 'pendiente') {
        Swal.fire({ icon: 'info', title: 'Asigna un operador', text: 'Primero debes asignar un operador para avanzar el estado.' });
        return;
    }

    const nextEstado = nextEstadoMap[estado];
    if (!nextEstado) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se puede avanzar más el estado.' });
        return;
    }

    const fields = optionalFieldsMap[estado] || [];

    if (fields.length === 0) {
        router.patch(`/servicios/${props.servicio.id}/avanzar`, { estado: nextEstado }, {
            onError: (e) => Swal.fire({ icon: 'error', title: 'Error', text: Object.values(e).flat().join(', ') }),
        });
        return;
    }

    const html = fields.map(f =>
        `<div style="margin-bottom:10px"><label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;text-align:left">${f.label} <span style="font-weight:400;color:#999">(opcional)</span></label><input id="${f.name}" type="number" class="swal2-input" style="width:100%;margin:0"></div>`
    ).join('');

    Swal.fire({
        title: 'Avanzar estado',
        html,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const data = { estado: nextEstado };
            for (const f of fields) {
                const el = document.getElementById(f.name);
                if (el && el.value) data[f.name] = el.value;
            }
            return data;
        },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(`/servicios/${props.servicio.id}/avanzar`, result.value, {
                onError: (e) => Swal.fire({ icon: 'error', title: 'Error', text: Object.values(e).flat().join(', ') }),
            });
        }
    });
}

function asignarOperador() {
    const operadores = props.operadores || [];
    const unidades = props.unidades || [];

    if (!operadores.length) {
        Swal.fire({ icon: 'error', title: 'Sin operadores', text: 'No hay operadores disponibles.' });
        return;
    }

    const ops = operadores.map(o => `<option value="${o.id}">${o.empleado?.nombre || 'Operador #' + o.id}</option>`).join('');
    const unis = unidades.map(u => `<option value="${u.id}">${u.nombre || u.placa || u.numero_economico}</option>`).join('');

    Swal.fire({
        title: 'Asignar operador',
        html: `
            <div style="margin-bottom:10px"><label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;text-align:left">Operador</label><select id="op_operador_id" class="swal2-input" style="width:100%;margin:0"><option value="">Seleccionar...</option>${ops}</select></div>
            <div style="margin-bottom:10px"><label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;text-align:left">Unidad (opcional)</label><select id="op_unidad_id" class="swal2-input" style="width:100%;margin:0"><option value="">Sin unidad</option>${unis}</select></div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Asignar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const operadorId = document.getElementById('op_operador_id').value;
            if (!operadorId) { Swal.showValidationMessage('Selecciona un operador'); return false; }
            return { operador_id: operadorId, unidad_id: document.getElementById('op_unidad_id').value || '' };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/servicios/${props.servicio.id}/asignar-operador`, result.value, {
                onError: (e) => Swal.fire({ icon: 'error', title: 'Error', text: Object.values(e).flat().join(', ') }),
            });
        }
    });
}

function cancelarServicio() {
    Swal.fire({
        title: 'Cancelar servicio',
        html: `
            <div class="text-left space-y-3">
                <label class="block text-sm font-medium text-gray-700">Tipo de incidencia</label>
                <select id="tipo_incidencia" class="swal2-input">
                    <option value="">Selecciona una opción</option>
                    <option value="cliente_cancela">Cliente cancela</option>
                    <option value="operador_siniestro">Operador siniestrado</option>
                    <option value="falla_mecanica">Falla mecánica</option>
                    <option value="llanta_ponchada">Llanta ponchada</option>
                    <option value="accidente">Accidente</option>
                    <option value="duplicado">Duplicado</option>
                    <option value="otro">Otro</option>
                </select>
                <label class="block text-sm font-medium text-gray-700">Motivo de cancelación</label>
                <textarea id="motivo_cancelacion" class="swal2-input" rows="3" placeholder="Describe el motivo..."></textarea>
            </div>
        `,
        focusConfirm: false,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Volver',
        preConfirm: () => {
            const tipo = document.getElementById('tipo_incidencia').value;
            const motivo = document.getElementById('motivo_cancelacion').value;
            if (!tipo) {
                Swal.showValidationMessage('Selecciona un tipo de incidencia');
                return false;
            }
            if (!motivo.trim()) {
                Swal.showValidationMessage('Escribe el motivo de cancelación');
                return false;
            }
            return { tipo_incidencia: tipo, motivo_cancelacion: motivo.trim() };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/servicios/${props.servicio.id}/cancelar`, result.value, {
                onError: (e) => Swal.fire({ icon: 'error', title: 'Error', text: Object.values(e).flat().join(', ') }),
            });
        }
    });
}

function statusClass(estado) {
    const map = {
        pendiente: 'default',
        asignado: 'info',
        en_ruta: 'warning',
        en_sitio: 'warning',
        cargando: 'warning',
        en_transporte: 'info',
        finalizado: 'success',
        cancelado: 'danger',
    };
    return map[estado?.toLowerCase()] || 'default';
}
</script>
