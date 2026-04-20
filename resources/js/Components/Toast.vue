<script setup>
import { ref, onMounted } from 'vue';
import { X, CheckCircle2, AlertCircle, Info } from 'lucide-vue-next';

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success'
    },
    duration: {
        type: Number,
        default: 5000
    }
});

const isVisible = ref(false);
const emit = defineEmits(['close']);

const hide = () => {
    isVisible.value = false;
    setTimeout(() => emit('close'), 500);
};

onMounted(() => {
    if (props.message) {
        isVisible.value = true;
        setTimeout(hide, props.duration);
    }
});
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-500 transition"
        enter-from-class="translate-y-4 opacity-0 scale-95"
        enter-to-class="translate-y-0 opacity-100 scale-100"
        leave-active-class="transition ease-in duration-400"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="isVisible"
            class="fixed bottom-6 right-6 z-[100] max-w-sm w-full bg-teraq-surface/90 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden pointer-events-auto"
        >
            <div class="p-5">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 mt-1">
                        <div v-if="type === 'success'" class="p-2 bg-emerald-500/20 rounded-xl">
                            <CheckCircle2 class="h-6 w-6 text-emerald-400" />
                        </div>
                        <div v-else-if="type === 'error'" class="p-2 bg-red-500/20 rounded-xl">
                            <AlertCircle class="h-6 w-6 text-red-400" />
                        </div>
                        <div v-else class="p-2 bg-teraq-primary/20 rounded-xl">
                            <Info class="h-6 w-6 text-teraq-primary" />
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-white/40 uppercase tracking-[0.2em] mb-1">
                            {{ type === 'success' ? 'Confirmed' : type === 'error' ? 'Notice' : 'Information' }}
                        </p>
                        <p class="text-sm text-white font-semibold leading-relaxed">
                            {{ message }}
                        </p>
                    </div>
                    <button
                        @click="hide"
                        class="p-2 rounded-xl text-white/20 hover:text-white hover:bg-white/5 transition-all"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>
            </div>
            
            <!-- Progress Bar Animation -->
            <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-teraq-primary to-teraq-secondary animate-toast-progress" :style="`animation-duration: ${duration}ms`"></div>
        </div>
    </Transition>
</template>

<style>
@keyframes toast-progress {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-toast-progress {
    animation-name: toast-progress;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}
</style>
