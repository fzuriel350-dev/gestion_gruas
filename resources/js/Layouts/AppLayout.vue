<template>
    <div class="min-h-screen overflow-x-hidden" :style="rootStyle">
        <div v-if="sidebarOpen" class="fixed inset-0 bg-black/50 z-30 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside class="fixed top-0 left-0 z-40 h-screen w-[260px] flex flex-col shrink-0 transition-transform duration-300 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            :style="sidebarStyle">
            <div class="flex items-center" style="margin-bottom:32px;padding-left:6px;gap:14px;">
                <img v-if="empresa?.logo" :src="empresa.logo" style="width:70px;height:70px;border-radius:16px;object-fit:cover;box-shadow:inset -2px -2px 6px rgba(0,0,0,0.15),inset 2px 2px 6px #fff;" />
                <div v-else style="width:70px;height:70px;background:#A5F3FC;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:bold;color:var(--geg-text);box-shadow:inset -2px -2px 6px rgba(0,0,0,0.15),inset 2px 2px 6px #fff;">
                    {{ empresa?.nombre?.charAt(0) || 'C' }}
                </div>
                <span style="font-size:24px;font-weight:bold;color:var(--geg-text);letter-spacing:1px;">{{ empresa?.nombre || 'SIGESGA' }}</span>
            </div>
            <nav class="menu" style="flex-grow:1;overflow-y:auto;">
                <template v-for="section in sidebarSections" :key="section.title">
                    <div class="section-header" @click="toggleSection(section.title)"
                        style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;margin:4px 0 2px;border-radius:14px;cursor:pointer;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--geg-text-secondary);transition:all 0.2s;"
                        @mouseenter="$event.currentTarget.style.background='rgba(255,255,255,0.3)'"
                        @mouseleave="$event.currentTarget.style.background='transparent'">
                        <span>{{ section.title }}</span>
                        <svg class="w-3 h-3 transition-transform duration-200" :style="{ transform: openSections[section.title] ? 'rotate(180deg)' : 'rotate(0deg)' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div v-show="openSections[section.title]" style="display:flex;flex-direction:column;gap:4px;margin-bottom:6px;">
                        <a v-for="item in section.items" :key="item.href"
                            :href="item.href"
                            @click="closeSection(section.title)"
                            style="display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:16px;text-decoration:none;font-weight:500;font-size:13.5px;transition:all 0.25s ease;color:var(--geg-text);"
                            :style="isActive(item.route) ? { background: 'var(--geg-primary)', color: 'white', boxShadow: '4px 4px 12px color-mix(in srgb, var(--geg-primary) 50%, transparent), inset -2px -2px 5px rgba(0,0,0,0.15), inset 2px 2px 5px rgba(255,255,255,0.35)' } : {}"
                            @mouseenter="!isActive(item.route) && ($event.currentTarget.style.background='rgba(255,255,255,0.4)')"
                            @mouseleave="!isActive(item.route) && ($event.currentTarget.style.background='transparent')">
                            <span v-html="item.icon" class="w-5 h-5 shrink-0"></span>
                            <span>{{ item.label }}</span>
                            <span v-if="item.badge" class="ml-auto" style="background:rgba(255,255,255,0.3);color:white;font-size:10px;padding:1px 7px;border-radius:8px;font-weight:600;">{{ item.badge }}</span>
                        </a>
                    </div>
                </template>
            </nav>

            <div class="shrink-0" style="display:flex;flex-direction:column;gap:8px;">
                <a :href="user?.role === 'cliente' ? '/panel/perfil' : '/profile'"
                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:16px;text-decoration:none;font-size:13px;font-weight:600;color:var(--geg-text);transition:all 0.2s;"
                    @mouseenter="$event.target.style.background='rgba(255,255,255,0.5)'"
                    @mouseleave="$event.target.style.background='transparent'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Mi Perfil</span>
                </a>
            <div style="background:white;padding:12px;border-radius:20px;display:flex;align-items:center;gap:12px;font-weight:bold;box-shadow:0px 8px 16px rgba(0,0,0,0.05),inset -3px -3px 6px rgba(0,0,0,0.05),inset 3px 3px 6px #fff;">
                <div v-if="user?.foto_perfil_url" class="w-10 h-10 rounded-full overflow-hidden shrink-0" style="box-shadow:inset -2px -2px 5px rgba(0,0,0,0.1),inset 2px 2px 5px #fff;">
                    <img :src="user.foto_perfil_url" class="w-full h-full object-cover">
                </div>
                <div v-else class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shrink-0" style="background:linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));color:black;box-shadow:inset -2px -2px 5px rgba(0,0,0,0.1),inset 2px 2px 5px #fff;">
                    {{ user?.name?.substring(0, 2) || '?' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div style="font-size:13px;color:var(--geg-text);">{{ user?.name || 'Usuario' }}</div>
                    <div style="font-size:11px;color:var(--geg-text-secondary);text-transform:capitalize;">{{ user?.role || '' }}</div>
                </div>
            </div>
                <form method="POST" action="/logout" style="margin:0;">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <button type="submit"
                        style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:16px;border:none;font-size:13px;font-weight:600;color:var(--geg-text);background:transparent;cursor:pointer;transition:all 0.2s;"
                        @mouseenter="$event.target.style.background='rgba(255,255,255,0.5)'"
                        @mouseleave="$event.target.style.background='transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-h-screen lg:ml-[260px] min-w-0">
            <header class="px-5 lg:px-7 py-3.5 flex items-center gap-5 sticky top-0 z-20"
                :style="{ background: 'var(--geg-clay-card)', boxShadow: '0 4px 12px var(--geg-clay-shadow-dark)' }">
                <button class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100" @click="sidebarOpen = !sidebarOpen">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-baseline gap-3 flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight truncate">{{ title }}</h1>
                    <span class="text-xs text-gray-500 font-medium hidden sm:inline whitespace-nowrap">{{ currentDate }}</span>
                </div>

                <div class="flex items-center gap-4 shrink-0">
                    <a :href="notifUrl" class="relative w-9 h-9 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center text-base hover-topbar transition-all">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span v-if="noLeidas > 0" class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 border-2 border-white">{{ noLeidas > 99 ? '99+' : noLeidas }}</span>
                    </a>
                    <div class="flex items-center gap-2.5 cursor-pointer px-2 py-1.5 rounded-xl hover:bg-gray-50">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-semibold text-gray-900">{{ user?.name }}</div>
                            <div class="text-[10.5px] text-gray-500 font-medium capitalize">{{ user?.role }}</div>
                        </div>
                        <div v-if="user?.foto_perfil_url" class="w-9 h-9 rounded-xl overflow-hidden shrink-0" style="box-shadow:inset -2px -2px 4px rgba(0,0,0,0.08),inset 2px 2px 4px #fff;">
                            <img :src="user.foto_perfil_url" class="w-full h-full object-cover">
                        </div>
                        <div v-else class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-black shrink-0"
                            :style="{ background: `linear-gradient(135deg, ${empresa?.color || '#FFD500'}, ${empresa?.color_secundario || empresa?.color || '#FFD500'})` }">
                            {{ user?.name?.substring(0, 2) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="p-5 lg:p-7 animate-fade-in-up">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    title: { type: String, default: 'Dashboard' },
});

const page = usePage();
const user = computed(() => page.props.auth?.user || page.props.user);
const empresa = computed(() => page.props.empresa);
const serviciosActivos = computed(() => page.props.serviciosActivos || 0);
const noLeidas = computed(() => page.props.noLeidas || 0);
const csrfToken = computed(() => page.props.csrf_token || '');

const isAdmin = computed(() => user.value?.role === 'admin');
const isCotizador = computed(() => user.value?.role === 'cotizador');
const isOperador = computed(() => user.value?.role === 'operador');
const isCliente = computed(() => user.value?.role === 'cliente');
const isEmpleado = computed(() => isAdmin.value || isCotizador.value || isOperador.value);

const rootStyle = computed(() => {
    const base = empresa.value?.color || '#FFD500';
    const secondary = empresa.value?.color_secundario || '#E3DFFD';
    return {
        background: 'var(--geg-clay-bg)',
        color: 'var(--geg-text)',
        '--geg-primary': base,
        '--geg-primary-dark': empresa.value?.color_secundario || base,
        '--geg-sidebar-bg': secondary,
    };
});

function hexToLuma(hex) {
    if (!hex) return 1;
    const h = hex.replace('#', '');
    const r = parseInt(h.substring(0, 2), 16);
    const g = parseInt(h.substring(2, 4), 16);
    const b = parseInt(h.substring(4, 6), 16);
    return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
}

const sidebarStyle = computed(() => {
    const secondary = empresa.value?.color_secundario || '#E3DFFD';
    const luma = hexToLuma(secondary);
    const textColor = luma > 0.5 ? '#1f2937' : '#ffffff';
    const textSecondary = luma > 0.5 ? '#6b7280' : '#d1d5db';
    return {
        background: 'var(--geg-sidebar-bg)',
        borderRadius: '0 30px 30px 0',
        padding: '30px 20px',
        justifyContent: 'space-between',
        boxShadow: '10px 10px 20px rgba(0,0,0,0.05), inset -5px -5px 10px rgba(0,0,0,0.1), inset 5px 5px 10px rgba(255,255,255,0.9)',
        '--geg-text': textColor,
        '--geg-text-secondary': textSecondary,
    };
});

const roleLabel = computed(() => {
    const labels = { admin: 'Panel de Administración', cotizador: 'Panel de Cotización', operador: 'Panel de Operador', cliente: 'Panel de Cliente' };
    return labels[user.value?.role] || '';
});

const notifUrl = computed(() => {
    if (isCliente.value) return '/panel/notificaciones';
    if (isCotizador.value) return '/cotizador/notificaciones';
    if (isOperador.value) return '/operador/notificaciones';
    return '/notificaciones';
});

const sidebarOpen = ref(false);

const currentDate = computed(() => {
    const d = new Date();
    const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return `${d.getDate()} de ${months[d.getMonth()]}, ${d.getFullYear()}`;
});

const isActive = (route) => {
    if (!route) return false;
    return window.location.pathname.startsWith(route);
};

const icons = {
    dashboard: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>',
    cotizaciones: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
    servicios: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
    clientes: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
    aseguradoras: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
    convenios: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
    tipos_servicio: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>',
    usuarios: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292V4.354zM15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m5-9.949a4 4 0 110 5.292V4.354z" /></svg>',
    empleados: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
    operadores: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
    unidades: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
    oficinas: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
    cancelaciones: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>',
    facturas: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
    notificaciones: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>',
    configuracion: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
};

watch(() => page.props.flash, (flash) => {
    if (flash?.success) Swal.fire({ icon: 'success', title: 'Éxito', text: flash.success, timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
    if (flash?.error) Swal.fire({ icon: 'error', title: 'Error', text: flash.error, timer: 4000, showConfirmButton: false, toast: true, position: 'top-end' });
}, { deep: true });

const openSections = ref({});

function toggleSection(title) {
    openSections.value[title] = !openSections.value[title];
}

function closeSection(title) {
    openSections.value[title] = false;
}

const sidebarSections = computed(() => {
    const sections = [];

    const principal = { title: 'Principal', visible: true, items: [
        { label: 'Dashboard', href: '/dashboard', route: '/dashboard', icon: icons.dashboard },
    ]};
    sections.push(principal);

    if (isAdmin.value || isCotizador.value) {
        sections.push({ title: 'Operación', visible: true, items: [
            { label: 'Cotizaciones', href: '/cotizaciones', route: '/cotizaciones', icon: icons.cotizaciones },
            { label: 'Servicios', href: '/servicios', route: '/servicios', icon: icons.servicios, badge: serviciosActivos.value > 0 ? serviciosActivos.value : null },
        ]});
        sections.push({ title: 'Catálogos', visible: true, items: [
            { label: 'Clientes', href: '/clientes', route: '/clientes', icon: icons.clientes },
            { label: 'Aseguradoras', href: '/aseguradoras', route: '/aseguradoras', icon: icons.aseguradoras },
            { label: 'Tipos de Servicio', href: '/tipos-servicio', route: '/tipos-servicio', icon: icons.tipos_servicio },
            { label: 'Convenios', href: '/convenios', route: '/convenios', icon: icons.convenios },
        ]});
    }

    if (isAdmin.value) {
        sections.push({ title: 'Administración', visible: true, items: [
            { label: 'Usuarios', href: '/usuarios', route: '/usuarios', icon: icons.usuarios },
            { label: 'Empleados', href: '/empleados', route: '/empleados', icon: icons.empleados },
            { label: 'Operadores', href: '/operadores', route: '/operadores', icon: icons.operadores },
            { label: 'Unidades', href: '/unidades', route: '/unidades', icon: icons.unidades },
            { label: 'Oficinas', href: '/oficinas', route: '/oficinas', icon: icons.oficinas },
            { label: 'Cancelaciones', href: '/autorizaciones-cancelacion', route: '/autorizaciones-cancelacion', icon: icons.cancelaciones },
            { label: 'Facturas', href: '/facturas', route: '/facturas', icon: icons.facturas },
            { label: 'Notificaciones', href: '/notificaciones', route: '/notificaciones', icon: icons.notificaciones, badge: noLeidas.value > 0 ? noLeidas.value : null },
            { label: 'Configuración', href: '/configuracion', route: '/configuracion', icon: icons.configuracion },
        ]});
    }

    if (isOperador.value) {
        sections.push({ title: 'Mis servicios', visible: true, items: [
            { label: 'Servicios activos', href: '/servicios', route: '/servicios', icon: icons.servicios },
            { label: 'Notificaciones', href: '/operador/notificaciones', route: '/operador/notificaciones', icon: icons.notificaciones, badge: noLeidas.value > 0 ? noLeidas.value : null },
        ]});
    }

    if (isCotizador.value) {
        sections.push({ title: 'Cotizaciones', visible: true, items: [
            { label: 'Mis cotizaciones', href: '/cotizaciones', route: '/cotizaciones', icon: icons.cotizaciones },
            { label: 'Servicios', href: '/servicios', route: '/servicios', icon: icons.servicios },
            { label: 'Notificaciones', href: '/cotizador/notificaciones', route: '/cotizador/notificaciones', icon: icons.notificaciones, badge: noLeidas.value > 0 ? noLeidas.value : null },
        ]});
    }

    if (isCliente.value) {
        sections.push({ title: 'Servicios', visible: true, items: [
            { label: 'Mis cotizaciones', href: '/panel/cotizaciones', route: '/panel/cotizaciones', icon: icons.cotizaciones },
            { label: 'Mis servicios', href: '/panel/servicios', route: '/panel/servicios', icon: icons.servicios, badge: serviciosActivos.value > 0 ? serviciosActivos.value : null },
            { label: 'Notificaciones', href: '/panel/notificaciones', route: '/panel/notificaciones', icon: icons.notificaciones, badge: noLeidas.value > 0 ? noLeidas.value : null },
        ]});
    }

    return sections;
});
</script>
