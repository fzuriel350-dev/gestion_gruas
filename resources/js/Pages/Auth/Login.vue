<template>
    <div class="min-h-screen flex items-center justify-center px-4" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
        <div class="w-full max-w-md bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8" style="animation: fadeInUp 0.8s ease-out both;">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4"
                    style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
                    <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Iniciar Sesión</h1>
                <p class="text-sm text-gray-400 mt-1">Accede a tu cuenta</p>
            </div>

            <div v-if="$page.props.flash?.error" class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-sm text-red-400 font-medium">
                {{ $page.props.flash.error }}
            </div>

            <div v-if="$page.props.errors?.email" class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-sm text-red-400 font-medium">
                {{ $page.props.errors.email }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="email">Correo electrónico</label>
                    <input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 bg-white/10 border border-white/20 text-white placeholder-white/40"
                        :class="form.errors.email ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.email ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password">Contraseña</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm transition-all duration-200 bg-white/10 border border-white/20 text-white placeholder-white/40"
                        :class="form.errors.password ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor='rgba(255,255,255,0.2)'">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" v-model="form.remember" class="rounded border-white/20 bg-white/10 text-amber-500 focus:ring-amber-500/50">
                        Recordarme
                    </label>
                    <Link :href="route('password.request')" class="text-sm font-semibold hover:underline" style="color:var(--geg-yellow);">
                        ¿Olvidaste tu contraseña?
                    </Link>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full justify-center text-base py-3 font-bold rounded-xl transition-all duration-200"
                    style="background:var(--geg-yellow);color:black;"
                    :class="form.processing ? 'opacity-70' : 'hover:scale-[1.02]'">
                    <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span v-else>Iniciar sesión</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                ¿No tienes cuenta?
                <Link :href="route('register')" class="font-semibold hover:underline" style="color:var(--geg-yellow);">Regístrate</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

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
