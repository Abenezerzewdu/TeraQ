<script setup>
import { ref } from 'vue';
import { Eye, EyeOff } from 'lucide-vue-next';

const model = defineModel({
    type: String,
    required: true,
});

const showPassword = ref(false);
const input = ref(null);

const toggleVisibility = () => {
    showPassword.value = !showPassword.value;
};

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="relative group">
        <input
            :type="showPassword ? 'text' : 'password'"
            class="w-full bg-white/5 border-white/10 text-white rounded-xl py-3 px-4 focus:ring-teraq-primary/50 focus:border-teraq-primary transition-all duration-200 placeholder:text-teraq-muted/50"
            v-model="model"
            ref="input"
            v-bind="$attrs"
        />
        <button
            type="button"
            @click="toggleVisibility"
            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-lg text-teraq-muted hover:text-white transition-colors focus:outline-none"
            title="Toggle Password Visibility"
        >
            <Eye v-if="!showPassword" class="h-5 w-5" />
            <EyeOff v-else class="h-5 w-5" />
        </button>
    </div>
</template>
