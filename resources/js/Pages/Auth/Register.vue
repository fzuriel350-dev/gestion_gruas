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
                <h1 class="text-2xl font-extrabold text-white">Crear cuenta</h1>
                <p class="text-sm text-gray-400 mt-1">Registrate para comenzar</p>
            </div>

            <div v-if="$page.props.errors?.email" class="mb-4 p-3 rounded-2xl bg-red-500/10 text-sm text-red-400 font-medium" style="box-shadow:inset 2px 2px 6px rgba(0,0,0,0.2),inset -2px -2px 6px rgba(80,70,60,0.15);">
                {{ $page.props.errors.email }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="name">Nombre</label>
                    <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        :style="form.errors.name ? 'box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15),0 0 0 3px rgba(248,113,113,0.3)' : ''"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="email">Correo electronico</label>
                    <input id="email" type="email" v-model="form.email" required autocomplete="username"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        :style="form.errors.email ? 'box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15),0 0 0 3px rgba(248,113,113,0.3)' : ''"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password">Contrasena</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        :style="form.errors.password ? 'box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15),0 0 0 3px rgba(248,113,113,0.3)' : ''"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">

                    <div v-if="form.password.length > 0" class="mt-3 space-y-1.5">
                        <div v-for="rule in passwordRules" :key="rule.label" class="flex items-center gap-2 text-xs">
                            <div class="w-4 h-4 rounded-full flex items-center justify-center shrink-0"
                                :class="rule.met ? 'bg-green-500' : 'bg-white/10 border border-white/20'">
                                <svg v-if="rule.met" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span :class="rule.met ? 'text-green-400' : 'text-gray-500'">{{ rule.label }}</span>
                        </div>

                        <div class="pt-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold" :class="strengthColor">{{ strengthLabel }}</span>
                                <span class="text-[10px] text-gray-500">{{ strengthScore }}/4</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-300"
                                    :class="strengthBarColor"
                                    :style="{ width: (strengthScore / 4 * 100) + '%' }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-white/80 mb-1.5" for="password_confirmation">Confirmar contrasena</label>
                    <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 text-sm transition-all duration-200 text-white placeholder-white/40"
                        style="border:none;border-radius:16px;background:rgba(40,35,30,0.9);box-shadow:inset 3px 3px 8px rgba(0,0,0,0.3),inset -3px -3px 8px rgba(80,70,60,0.15);"
                        @focus="$event.target.style.background='rgba(50,45,40,0.95)'"
                        @blur="$event.target.style.background='rgba(40,35,30,0.9)'">
                </div>

                <button type="submit" :disabled="form.processing || !passwordValid"
                    class="w-full justify-center text-base py-3 font-bold transition-all duration-200"
                    style="border-radius:16px;box-shadow:4px 4px 8px rgba(0,0,0,0.3),-4px -4px 8px rgba(80,70,60,0.15);"
                    :class="(form.processing || !passwordValid) ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] hover:shadow-[6px_6px_12px_rgba(0,0,0,0.35),-6px_-6px_12px_rgba(80,70,60,0.2)]'"
                    :style="{ background: passwordValid ? 'var(--geg-yellow)' : '#555', color: 'black', borderRadius: '16px' }">
                    <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span v-else>Registrarse</span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-400">
                Ya tienes cuenta?
                <Link :href="route('login')" class="font-semibold hover:underline" style="color:var(--geg-yellow);">Inicia sesion</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    empresa: { type: Object, default: null },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const hasMinLength = computed(() => form.password.length >= 8);
const hasLowercase = computed(() => /[a-z]/.test(form.password));
const hasUppercase = computed(() => /[A-Z]/.test(form.password));
const hasNumber = computed(() => /[0-9]/.test(form.password));
const hasSymbol = computed(() => /[^a-zA-Z0-9]/.test(form.password));

const passwordRules = computed(() => [
    { label: 'Minimo 8 caracteres', met: hasMinLength.value },
    { label: 'Letras minusculas', met: hasLowercase.value },
    { label: 'Letras mayusculas', met: hasUppercase.value },
    { label: 'Al menos 1 numero', met: hasNumber.value },
    { label: 'Al menos 1 simbolo', met: hasSymbol.value },
]);

const strengthScore = computed(() => {
    let score = 0;
    if (hasMinLength.value) score++;
    if (hasLowercase.value) score++;
    if (hasUppercase.value) score++;
    if (hasNumber.value) score++;
    if (hasSymbol.value) score++;
    if (form.password.length >= 12) score = Math.min(score + 1, 4);
    return Math.min(score, 4);
});

const strengthLabel = computed(() => {
    if (strengthScore.value <= 1) return 'Muy facil';
    if (strengthScore.value === 2) return 'Facil';
    if (strengthScore.value === 3) return 'Media';
    return 'Segura';
});

const strengthColor = computed(() => {
    if (strengthScore.value <= 1) return 'text-red-400';
    if (strengthScore.value === 2) return 'text-orange-400';
    if (strengthScore.value === 3) return 'text-yellow-400';
    return 'text-green-400';
});

const strengthBarColor = computed(() => {
    if (strengthScore.value <= 1) return 'bg-red-500';
    if (strengthScore.value === 2) return 'bg-orange-500';
    if (strengthScore.value === 3) return 'bg-yellow-500';
    return 'bg-green-500';
});

const passwordValid = computed(() => strengthScore.value >= 3);

const submit = () => {
    form.post(route('register'), {
        onError: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
