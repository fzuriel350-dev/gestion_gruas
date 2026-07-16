<template>
    <div class="min-h-screen flex items-center justify-center px-4" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
        <div class="w-full max-w-md bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8" style="animation: fadeInUp 0.8s ease-out both;">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4"
                    style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
                    <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Crear cuenta</h1>
                <p class="text-sm text-gray-400 mt-1">Regístrate para comenzar</p>
            </div>

            <div v-if="$page.props.errors?.email" class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-sm text-red-400 font-medium">
                {{ $page.props.errors.email }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="name">Nombre</label>
                    <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm bg-white/10 border border-white/20 text-white placeholder-white/40 transition-all duration-200"
                        :class="form.errors.name ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.name ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="email">Correo electrónico</label>
                    <input id="email" type="email" v-model="form.email" required autocomplete="username"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm bg-white/10 border border-white/20 text-white placeholder-white/40 transition-all duration-200"
                        :class="form.errors.email ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.email ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password">Contraseña</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm bg-white/10 border border-white/20 text-white placeholder-white/40 transition-all duration-200"
                        :class="form.errors.password ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.password ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm bg-white/10 border border-white/20 text-white placeholder-white/40 transition-all duration-200"
                        :class="form.errors.password_confirmation ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.password_confirmation ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full justify-center text-base py-3 font-bold rounded-xl transition-all duration-200"
                    style="background:var(--geg-yellow);color:black;"
                    :class="form.processing ? 'opacity-70' : 'hover:scale-[1.02]'">
                    <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span v-else>Registrarse</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                ¿Ya tienes cuenta?
                <Link :href="route('login')" class="font-semibold hover:underline" style="color:var(--geg-yellow);">Inicia sesión</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onError: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
