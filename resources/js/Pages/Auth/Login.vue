<template>
    <div class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">
        <div v-if="empresa && empresa.imagen_fondo"
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            :style="{ backgroundImage: `url(${empresa.imagen_fondo})` }">
        </div>
        <div v-if="empresa && empresa.imagen_fondo" class="absolute inset-0 bg-black/60"></div>
        <div v-if="!empresa || !empresa.imagen_fondo" class="absolute inset-0" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);"></div>
        <div class="relative z-10 w-full max-w-md p-8" style="border-radius:20px;background:rgba(50,45,40,0.85);box-shadow:8px 8px 16px rgba(0,0,0,0.3),-8px -8px 16px rgba(80,70,60,0.2);animation:fadeInUp 0.8s ease-out both;">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-white">Iniciar Sesion</h1>
                <p class="text-sm text-gray-400 mt-1">Accede a tu cuenta</p>
            </div>

            <div v-if="$page.props.flash?.error" class="mb-4 p-3 rounded-2xl bg-red-500/10 text-sm text-red-400 font-medium" style="box-shadow:inset 2px 2px 6px rgba(0,0,0,0.2),inset -2px -2px 6px rgba(80,70,60,0.15);">
                {{ $page.props.flash.error }}
            </div>

            <div v-if="$page.props.errors?.email" class="mb-4 p-3 rounded-2xl bg-red-500/10 text-sm text-red-400 font-medium" style="box-shadow:inset 2px 2px 6px rgba(0,0,0,0.2),inset -2px -2px 6px rgba(80,70,60,0.15);">
                {{ $page.props.errors.email }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="email">Correo electronico</label>
                    <input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        :style="form.errors.email ? 'box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15),0 0 0 3px rgba(248,113,113,0.3)' : ''"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password">Contrasena</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        :style="form.errors.password ? 'box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15),0 0 0 3px rgba(248,113,113,0.3)' : ''"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" v-model="form.remember" class="rounded border-white/20 bg-white/10 text-amber-500 focus:ring-amber-500/50">
                        Recordarme
                    </label>
                    <Link :href="route('password.request')" class="text-sm font-semibold hover:underline" style="color:var(--geg-yellow);">
                        Olvidaste tu contrasena?
                    </Link>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full justify-center text-base py-3 font-bold transition-all duration-200"
                    style="border-radius:16px;background:var(--geg-yellow);color:black;box-shadow:4px 4px 8px rgba(0,0,0,0.3),-4px -4px 8px rgba(80,70,60,0.15);"
                    :class="form.processing ? 'opacity-70' : 'hover:scale-[1.02] hover:shadow-[6px_6px_12px_rgba(0,0,0,0.35),-6px_-6px_12px_rgba(80,70,60,0.2)]'">
                    <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span v-else>Iniciar sesion</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                No tienes cuenta?
                <Link :href="route('register')" class="font-semibold hover:underline" style="color:var(--geg-yellow);">Registrate</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
defineProps({
    empresa: { type: Object, default: null },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onError: () => form.reset('password'),
    });
};
</script>
