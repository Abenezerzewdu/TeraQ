<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full bg-white/5 border-white/10 text-white rounded-xl py-3 px-4 focus:ring-teraq-primary/50 focus:border-teraq-primary transition-all duration-200 placeholder:text-teraq-muted/50"
        v-model="model"
        ref="input"
    />
</template>

