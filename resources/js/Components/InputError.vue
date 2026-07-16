<template>
    <div v-if="message" class="text-xs text-red-600 mt-1 font-medium">{{ message }}</div>
</template>

<script setup>
defineProps({
    message: { type: String, default: '' },
});
</script>
