<script setup>
import { AlertCircle } from 'lucide-vue-next';

defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="transform -translate-y-2 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-2 opacity-0"
    >
        <div v-if="message" class="flex items-center gap-2 px-3 py-2 mt-2 bg-red-500/10 border border-red-500/20 rounded-lg">
            <AlertCircle class="w-3.5 h-3.5 text-red-500" />
            <p class="text-[10px] sm:text-xs font-bold text-red-400 uppercase tracking-widest">
                {{ message }}
            </p>
        </div>
    </Transition>
</template>
