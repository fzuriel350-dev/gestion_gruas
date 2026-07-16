<template>
    <div v-if="links && links.length > 3" class="flex items-center justify-between mt-5 gap-4">
        <div class="text-xs text-gray-500 font-medium shrink-0">
            Mostrando {{ meta.from }} a {{ meta.to }} de {{ meta.total }} registros
        </div>
        <div class="flex items-center gap-1.5">
            <template v-for="(link, i) in links" :key="i">
                <Link v-if="link.url"
                    :href="link.url"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150"
                    :class="link.active
                        ? 'text-black shadow-sm' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                    :style="link.active ? { background: 'var(--geg-yellow)' } : {}"
                    v-html="link.label" />
                <span v-else class="px-2 py-1.5 text-xs text-gray-400" v-html="link.label"></span>
            </template>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    meta: { type: Object, required: true },
    links: { type: Array, required: true },
});
</script>
