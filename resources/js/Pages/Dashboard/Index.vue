<template>
    <AppLayout title="Dashboard">
        <template v-if="role === 'operador'">
            <div class="card p-6 mb-6" style="background:var(--geg-card-orange);border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold" style="color:var(--geg-text);">Bienvenido, <span style="color:var(--geg-btn-orange);">{{ user.name }}</span></h2>
                        <p style="color:var(--geg-text-secondary);font-size:14px;font-weight:500;">Tienes <strong style="color:var(--geg-text);">{{ serviciosAsignados }} servicios asignados</strong> hoy</p>
                    </div>
                </div>
            </div>
            <div class="metrics-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
                <div class="metric-card" style="background:var(--geg-card-purple);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);" @click="$inertia.visit('/servicios')">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Asignados hoy</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ serviciosAsignados }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:var(--geg-success);background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">Activos ahora</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-green);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Completados hoy</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ serviciosHoy }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:var(--geg-success);background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">Completado</span>
                </div>
            </div>
        </template>

        <template v-else-if="role === 'cliente'">
            <div class="card p-6 mb-6" style="background:var(--geg-card-blue);border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                <div>
                    <h2 class="text-xl font-bold" style="color:var(--geg-text);">Hola, <span style="color:var(--geg-btn-orange);">{{ user.name }}</span></h2>
                    <p style="color:var(--geg-text-secondary);font-size:14px;">Bienvenido a tu panel de control</p>
                </div>
            </div>
            <div class="metrics-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
                <div class="metric-card" style="background:var(--geg-card-purple);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Cotizaciones pendientes</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ cotizacionesPendientes }}</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-green);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Servicios activos</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ serviciosActivos }}</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-orange);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Servicios finalizados</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ serviciosFinalizados }}</span>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex items-center justify-between gap-5 mb-6" style="background:var(--geg-card-purple);border-radius:24px;padding:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                <div>
                    <h2 style="font-size:28px;font-weight:800;color:var(--geg-text);">Analytics overview</h2>
                    <p style="color:var(--geg-text-secondary);font-size:14px;font-weight:500;">Bienvenido de nuevo, <strong style="color:var(--geg-text);">{{ user.name }}</strong></p>
                </div>
                <button class="btn-export" style="background:var(--geg-btn-orange);color:white;border:none;padding:12px 24px;border-radius:18px;font-weight:bold;cursor:pointer;box-shadow:4px 6px 15px rgba(255,138,101,0.3),inset -3px -3px 6px rgba(0,0,0,0.15),inset 3px 3px 6px rgba(255,255,255,0.3);">📥 Export report</button>
            </div>

            <div class="metrics-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:24px;">
                <div class="metric-card" style="background:var(--geg-card-purple);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Cotizaciones pendientes</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ stats.cotizaciones_pendientes }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:#10B981;background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">Pendientes</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-green);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Servicios activos</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ stats.servicios_activos }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:#10B981;background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">+8.1% ↑</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-orange);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Operadores disponibles</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ stats.operadores_disponibles }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:#EF4444;background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">{{ stats.operadores_ocupados }} ocupados ↓</span>
                </div>
                <div class="metric-card" style="background:var(--geg-card-blue);padding:20px;border-radius:24px;box-shadow:5px 10px 20px rgba(0,0,0,0.03),inset -4px -4px 8px rgba(0,0,0,0.05),inset 4px 4px 8px rgba(255,255,255,0.9);">
                    <span style="font-size:14px;color:var(--geg-text-secondary);font-weight:600;">Ingresos del mes</span>
                    <span style="font-size:32px;font-weight:800;color:var(--geg-text);">{{ formatCurrency(stats.ingresos_mes, moneda) }}</span>
                    <span style="display:inline-block;align-self:flex-start;padding:4px 10px;border-radius:10px;font-size:12px;font-weight:bold;color:#10B981;background:rgba(255,255,255,0.6);box-shadow:inset -1px -1px 3px rgba(0,0,0,0.05),inset 1px 1px 3px #fff;">+2.8% ↑</span>
                </div>
            </div>

            <div class="middle-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
                <div style="background:var(--geg-card-purple);border-radius:24px;padding:24px;box-shadow:inset -4px -4px 10px rgba(0,0,0,0.02),inset 4px 4px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-weight:700;margin-bottom:16px;color:var(--geg-text);">Servicios por día</h3>
                    <div class="flex items-end gap-3" style="height:180px;padding-top:16px;">
                        <div v-for="day in dias" :key="day.label" class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                            <span style="font-size:11px;font-weight:bold;color:var(--geg-text-secondary);">{{ day.value }}</span>
                            <div class="chart-bar" :style="{ height: day.height + '%' }"></div>
                            <span style="font-size:11px;color:var(--geg-text-secondary);font-weight:500;">{{ day.label }}</span>
                        </div>
                    </div>
                </div>
                <div style="background:var(--geg-card-green);border-radius:24px;padding:24px;box-shadow:inset -4px -4px 10px rgba(0,0,0,0.02),inset 4px 4px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-weight:700;margin-bottom:16px;color:var(--geg-text);">Actividad reciente</h3>
                    <div class="activity-list" style="display:flex;flex-direction:column;gap:10px;">
                        <div v-for="activity in actividades" :key="activity.text" class="flex items-center justify-between" style="background:white;padding:12px 20px;border-radius:15px;box-shadow:3px 5px 10px rgba(0,0,0,0.02),inset -2px -2px 5px rgba(0,0,0,0.05),inset 2px 2px 5px #fff;">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" :class="`bg-${activity.dot}-600`"></div>
                                <span style="font-size:13px;color:var(--geg-text);"><strong>{{ activity.text.split(' ')[0] }}</strong>{{ activity.text.substring(activity.text.indexOf(' ')) }}</span>
                            </div>
                            <span style="color:var(--geg-text-secondary);font-size:13px;">{{ activity.time }}</span>
                        </div>
                        <div v-if="actividades.length === 0" style="text-align:center;padding:20px;color:var(--geg-text-secondary);font-size:14px;">Sin actividad reciente</div>
                    </div>
                </div>
            </div>

            <div style="background:var(--geg-card-orange);border-radius:24px;padding:24px;box-shadow:inset -4px -4px 10px rgba(0,0,0,0.02),inset 4px 4px 10px rgba(0,0,0,0.05);">
                <h3 style="font-weight:700;margin-bottom:15px;color:var(--geg-text);">Servicios recientes</h3>
                <div class="overflow-x-auto">
                    <table style="width:100%;font-size:14px;border-collapse:separate;border-spacing:0 8px;">
                        <thead>
                            <tr>
                                <th style="text-align:left;padding:8px 16px;font-size:12px;font-weight:700;color:var(--geg-text-secondary);">Folio</th>
                                <th style="text-align:left;padding:8px 16px;font-size:12px;font-weight:700;color:var(--geg-text-secondary);">Cliente</th>
                                <th style="text-align:left;padding:8px 16px;font-size:12px;font-weight:700;color:var(--geg-text-secondary);">Origen</th>
                                <th style="text-align:left;padding:8px 16px;font-size:12px;font-weight:700;color:var(--geg-text-secondary);">Destino</th>
                                <th style="text-align:left;padding:8px 16px;font-size:12px;font-weight:700;color:var(--geg-text-secondary);">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="svc in servicios" :key="svc.folio" style="background:white;border-radius:15px;box-shadow:3px 5px 10px rgba(0,0,0,0.02),inset -2px -2px 5px rgba(0,0,0,0.05),inset 2px 2px 5px #fff;">
                                <td style="padding:12px 16px;border-radius:15px 0 0 15px;font-weight:700;">{{ svc.folio }}</td>
                                <td style="padding:12px 16px;">{{ svc.cliente }}</td>
                                <td style="padding:12px 16px;">{{ svc.origen }}</td>
                                <td style="padding:12px 16px;">{{ svc.destino }}</td>
                                <td style="padding:12px 16px;border-radius:0 15px 15px 0;">
                                    <span class="status" :class="`status-${svc.class}`">
                                        <span class="status-dot"></span> {{ svc.estado }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="servicios.length === 0">
                                <td colspan="5" style="text-align:center;padding:24px;color:var(--geg-text-secondary);">Sin servicios recientes</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatCurrency } from '@/helpers.js';

const page = usePage();
const props = defineProps({
    role: { type: String, required: true },
    stats: { type: Object, default: () => ({}) },
    actividades: { type: Array, default: () => [] },
    servicios: { type: Array, default: () => [] },
    dias: { type: Array, default: () => [] },
    nuevosClientes: { type: Array, default: () => [] },
    cotizacionesPendientes: { type: Number, default: 0 },
    serviciosActivos: { type: Number, default: 0 },
    serviciosFinalizados: { type: Number, default: 0 },
    servicioActivo: { type: Object, default: null },
    serviciosAsignados: { type: Number, default: 0 },
    serviciosHoy: { type: Number, default: 0 },
    moneda: { type: String, default: '$' },
});

const user = computed(() => page.props.user);
const empresa = computed(() => page.props.empresa);
const empresaColor = computed(() => empresa.value?.color || '#FFD500');
const empresaColorDark = computed(() => empresa.value?.color_secundario || empresa.value?.color || '#FFD500');
const empresaColorLight = computed(() => `${empresaColor.value}22`);
</script>
