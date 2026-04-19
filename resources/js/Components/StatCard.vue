<script setup>
defineProps({
    label: String,
    value: [String, Number],
    unit: String,
    trend: {
        type: String, // 'up', 'down', 'stable'
        default: null
    },
    trendValue: String,
    color: {
        type: String,
        default: 'blue' // 'blue', 'green', 'orange', 'purple'
    }
});

const colors = {
    blue: 'from-blue-500/20 to-blue-600/5 text-blue-400 border-blue-500/20',
    green: 'from-emerald-500/20 to-emerald-600/5 text-emerald-400 border-emerald-500/20',
    orange: 'from-orange-500/20 to-orange-600/5 text-orange-400 border-orange-500/20',
    purple: 'from-purple-500/20 to-purple-600/5 text-purple-400 border-purple-500/20',
};
</script>

<template>
    <div class="glass-card p-6 flex flex-col justify-between h-full group relative overflow-hidden">
        <!-- Background Glow -->
        <div 
            class="absolute -right-4 -top-4 w-24 h-24 blur-[60px] opacity-20 transition-opacity duration-500 group-hover:opacity-40"
            :class="[
                color === 'blue' ? 'bg-blue-500' : '',
                color === 'green' ? 'bg-emerald-500' : '',
                color === 'orange' ? 'bg-orange-500' : '',
                color === 'purple' ? 'bg-purple-500' : '',
            ]"
        ></div>

        <div>
            <p class="text-teraq-muted text-xs uppercase tracking-widest font-bold mb-4">{{ label }}</p>
            <div class="flex items-baseline gap-2">
                <h2 class="text-4xl font-bold text-white tracking-tight">{{ value }}</h2>
                <span v-if="unit" class="text-teraq-muted text-lg font-medium">{{ unit }}</span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div v-if="trend" class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-white/5 border border-white/5">
                <span :class="[trend === 'up' ? 'text-emerald-400' : trend === 'down' ? 'text-red-400' : 'text-blue-400']">
                    {{ trend === 'up' ? '↑' : trend === 'down' ? '↓' : '•' }} {{ trendValue }}
                </span>
            </div>
            
            <slot name="icon" />
        </div>
    </div>
</template>
