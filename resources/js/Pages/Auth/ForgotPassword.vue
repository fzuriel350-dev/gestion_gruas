<template>
    <div class="min-h-screen flex items-center justify-center px-4" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
        <div class="w-full max-w-md bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8" style="animation: fadeInUp 0.8s ease-out both;">
            <div class="text-center mb-8">
                <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4"
                    style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
                    <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Recuperar contraseña</h1>
                <p class="text-sm text-gray-400 mt-1">Te enviaremos un enlace para restablecerla</p>
            </div>

            <div v-if="status" class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-sm text-emerald-400 font-medium">
                {{ status }}
            </div>

            <div v-if="$page.props.errors?.email" class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-sm text-red-400 font-medium">
                {{ $page.props.errors.email }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="email">Correo electrónico</label>
                    <input id="email" type="email" v-model="form.email" required autofocus autocomplete="email"
                        class="w-full px-3.5 py-2.5 rounded-lg text-sm bg-white/10 border border-white/20 text-white placeholder-white/40 transition-all duration-200"
                        :class="form.errors.email ? 'border-red-400' : 'border-white/20'"
                        style="border-width:1.5px;"
                        @focus="$event.target.style.borderColor='var(--geg-yellow)'"
                        @blur="$event.target.style.borderColor=form.errors.email ? 'rgb(248 113 113)' : 'rgba(255,255,255,0.2)'">
                </div>

                <button type="submit" :disabled="form.processing"
                    class="w-full justify-center text-base py-3 font-bold rounded-xl transition-all duration-200"
                    style="background:var(--geg-yellow);color:black;"
                    :class="form.processing ? 'opacity-70' : 'hover:scale-[1.02]'">
                    <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span v-else>Enviar enlace</span>
                </button>
            </form>

            <p class="mt-6 text-center">
                <Link :href="route('login')" class="text-sm font-semibold hover:underline" style="color:var(--geg-yellow);">Volver al inicio de sesión</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const status = computed(() => page.props?.flash?.status || page.props?.status || '');

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>
