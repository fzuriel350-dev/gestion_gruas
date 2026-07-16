<template>
    <AppLayout title="Dashboard">
        <template v-if="role === 'operador'">
            <div class="welcome-banner rounded-xl p-5 lg:p-7 mb-6 relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-[180px] h-[180px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.08) 0%, transparent 70%);"></div>
                <div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);"></div>
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Bienvenido, <span style="color: var(--geg-yellow);">{{ user.name }}</span></h2>
                    <p class="text-[13.5px] text-white/60">Tienes <strong class="text-white/90">{{ serviciosAsignados }} servicios asignados</strong> hoy</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: var(--geg-success);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ serviciosAsignados }}</div>
                        <div class="stat-label">Asignados hoy</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fef3c7; color: var(--geg-yellow-dark);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ serviciosHoy }}</div>
                        <div class="stat-label">Completados hoy</div>
                    </div>
                </div>
            </div>
        </template>

        <template v-else-if="role === 'cliente'">
            <div class="welcome-banner rounded-xl p-6 lg:p-8 mb-6 relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-[220px] h-[220px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.1) 0%, transparent 70%);"></div>
                <div class="absolute left-[60%] bottom-[-60px] w-[300px] h-[300px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.05) 0%, transparent 70%);"></div>
                <div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);"></div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">Hola, <span style="color: var(--geg-yellow);">{{ user.name }}</span></h2>
                        <p class="text-sm text-white/50">Bienvenido a tu panel de control</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-icon" :style="{ background: `linear-gradient(135deg, ${empresaColorLight}, color-mix(in srgb, ${empresaColor} 30%, white))`, color: empresaColorDark }">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ cotizacionesPendientes }}</div>
                        <div class="stat-label">Cotizaciones pendientes</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ serviciosActivos }}</div>
                        <div class="stat-label">Servicios activos</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ serviciosFinalizados }}</div>
                        <div class="stat-label">Servicios finalizados</div>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="welcome-banner rounded-xl p-5 lg:p-7 flex items-center justify-between gap-5 mb-6 relative overflow-hidden">
                <div class="absolute right-[-40px] top-[-40px] w-[180px] h-[180px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.08) 0%, transparent 70%);"></div>
                <div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);"></div>
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Bienvenido de nuevo, <span style="color: var(--geg-yellow);">{{ user.name }}</span></h2>
                    <p class="text-[13.5px] text-white/60">Hoy tienes <strong class="text-white/90">{{ stats.servicios_activos }} servicios activos</strong> y <strong class="text-white/90">{{ stats.cotizaciones_pendientes }} cotizaciones pendientes</strong> por revisar.</p>
                </div>
                <div class="flex gap-2.5 shrink-0 flex-wrap">
                    <a href="/cotizaciones" class="btn btn-primary">Ir a cotizaciones</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-icon" :style="{ background: empresaColorLight, color: empresaColorDark }">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ stats.cotizaciones_pendientes }}</div>
                        <div class="stat-label">Cotizaciones pendientes</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: var(--geg-success);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ stats.servicios_activos }}</div>
                        <div class="stat-label">Servicios activos</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fef3c7; color: var(--geg-yellow-dark);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ stats.operadores_disponibles }}</div>
                        <div class="stat-label">Operadores disponibles</div>
                        <div class="stat-trend down">{{ stats.operadores_ocupados }} ocupados</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ formatCurrency(stats.ingresos_mes, moneda) }}</div>
                        <div class="stat-label">Ingresos del mes</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5 mb-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Servicios por día</h3>
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full" :style="{ background: empresaColorLight, color: empresaColorDark }">Esta semana</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex items-end gap-3 h-[180px] pt-4">
                            <div v-for="day in dias" :key="day.label" class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                                <span class="text-[11px] font-bold text-gray-800 order-first">{{ day.value }}</span>
                                <div class="chart-bar" :style="{ height: day.height + '%' }"></div>
                                <span class="text-[11px] text-gray-500 font-medium">{{ day.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Actividad reciente</h3>
                    </div>
                    <div class="card-body py-2">
                        <div v-for="activity in actividades" :key="activity.text" class="flex items-start gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="activity-dot" :class="`activity-dot-${activity.dot}`"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-800 leading-tight">{{ activity.text }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ activity.time }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5">
                <div class="card">
                    <div class="card-header">
                        <h3>Servicios recientes</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Cliente</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Origen</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Destino</th>
                                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="svc in servicios" :key="svc.folio" class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm border-b border-gray-50"><strong>{{ svc.folio }}</strong></td>
                                    <td class="px-5 py-3 text-sm border-b border-gray-50">{{ svc.cliente }}</td>
                                    <td class="px-5 py-3 text-sm border-b border-gray-50">{{ svc.origen }}</td>
                                    <td class="px-5 py-3 text-sm border-b border-gray-50">{{ svc.destino }}</td>
                                    <td class="px-5 py-3 text-sm border-b border-gray-50">
                                        <span class="status" :class="`status-${svc.class}`">
                                            <span class="status-dot"></span> {{ svc.estado }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Nuevos clientes registrados</h3>
                    </div>
                    <div class="card-body py-2">
                        <div v-for="cliente in nuevosClientes" :key="cliente.name" class="flex items-start gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">{{ cliente.name.substring(0, 2) }}</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-800 font-medium">{{ cliente.name }}</div>
                                <div class="text-[11px] text-gray-500">{{ cliente.email }}</div>
                            </div>
                            <div class="text-[11px] text-gray-400 shrink-0">{{ cliente.time }}</div>
                        </div>
                        <div v-if="nuevosClientes.length === 0" class="text-center py-8 text-gray-400 text-sm">Sin registros recientes</div>
                    </div>
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
const empresaColor = computed(() => empresa.value?.color || '#f59e0b');
const empresaColorDark = computed(() => empresa.value?.color_secundario || empresa.value?.color || '#f59e0b');
const empresaColorLight = computed(() => `${empresaColor.value}22`);


</script>
