<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-semibold text-teraq-muted/80 mb-2 uppercase tracking-wider">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

