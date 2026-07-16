<template>
    <div class="min-h-screen flex items-center justify-center px-4" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
        <div class="w-full max-w-md bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8 text-center" style="animation: fadeInUp 0.8s ease-out both;">
            <h1 class="text-2xl font-bold text-white mb-4">Verificar correo electrónico</h1>
            <p class="text-gray-400 mb-6">
                Gracias por registrarte. Antes de continuar, verifica tu correo electrónico con el enlace que te enviamos.
            </p>
            <Link :href="route('verification.send')" method="post" as="button"
                class="px-6 py-2 rounded-xl font-bold transition-all duration-200"
                style="background:var(--geg-yellow);color:black;">
                Reenviar correo de verificación
            </Link>
            <Link :href="route('logout')" method="post" as="button"
                class="block mx-auto mt-4 text-sm text-gray-400 hover:text-white">
                Cerrar sesión
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
