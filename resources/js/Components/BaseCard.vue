<script setup>
defineProps({
    title: String,
    subtitle: String,
    gradient: {
        type: Boolean,
        default: false
    },
    noPadding: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div 
        class="glass-card overflow-hidden h-full"
        :class="[
            gradient ? 'bg-gradient-to-br from-white/5 to-transparent' : '',
            'hover:border-white/10 transition-colors duration-500'
        ]"
    >
        <div v-if="title || $slots.header" class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 v-if="title" class="text-white font-semibold flex items-center gap-2">
                    <slot name="icon"></slot>
                    {{ title }}
                </h3>
                <p v-if="subtitle" class="text-teraq-muted text-xs mt-1">{{ subtitle }}</p>
                <slot name="header"></slot>
            </div>
            <slot name="action"></slot>
        </div>
        
        <div :class="{ 'p-6': !noPadding }">
            <slot />
        </div>
        
        <div v-if="$slots.footer" class="px-6 py-4 border-t border-white/5 bg-white/[0.02]">
            <slot name="footer" />
        </div>
    </div>
</template>
