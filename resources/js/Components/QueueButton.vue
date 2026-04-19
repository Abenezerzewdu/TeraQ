<script setup>
import { ArrowRight } from 'lucide-vue-next';

defineProps({
    type: {
        type: String,
        default: 'button'
    },
    variant: {
        type: String,
        default: 'primary' // 'primary', 'secondary', 'outline'
    },
    loading: Boolean,
    showArrow: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <button
        :type="type"
        :disabled="loading"
        class="group relative inline-flex items-center justify-center gap-2 overflow-hidden transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98]"
        :class="[
            variant === 'primary' ? 'btn-gradient' : '',
            variant === 'secondary' ? 'bg-white/5 hover:bg-white/10 text-white border border-white/10 px-6 py-3 rounded-xl' : '',
            variant === 'outline' ? 'bg-transparent border border-teraq-primary/30 text-teraq-primary hover:bg-teraq-primary/10 px-6 py-3 rounded-xl' : ''
        ]"
    >
        <!-- Loading Spinner -->
        <svg v-if="loading" class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <slot v-else />

        <ArrowRight 
            v-if="showArrow && !loading" 
            class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" 
        />

        <!-- Shine Effect -->
        <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-[-20deg]"></div>
    </button>
</template>
