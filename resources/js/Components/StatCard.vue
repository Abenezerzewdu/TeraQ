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
    <div class="glass-card p-6 flex flex-col justify-between h-full group relative overflow-hidden active:scale-[0.98] transition-all duration-300">
        <!-- Background Glow -->
        <div 
            class="absolute -right-4 -top-4 w-32 h-32 blur-[60px] opacity-10 transition-opacity duration-700 group-hover:opacity-40"
            :class="[
                color === 'blue' ? 'bg-blue-500' : '',
                color === 'green' ? 'bg-emerald-500' : '',
                color === 'orange' ? 'bg-orange-500' : '',
                color === 'purple' ? 'bg-purple-500' : '',
            ]"
        ></div>

        <div class="relative z-10">
            <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-4 opacity-70 group-hover:opacity-100 transition-opacity">{{ label }}</p>
            <div class="flex items-baseline gap-2">
                <h2 class="text-4xl font-black text-white tracking-tighter group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-white group-hover:to-white/50 transition-all duration-500">{{ value }}</h2>
                <span v-if="unit" class="text-teraq-muted text-lg font-medium opacity-50">{{ unit }}</span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between relative z-10">
            <div v-if="trend" class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-white/5 border border-white/10 backdrop-blur-md">
                <span :class="[trend === 'up' ? 'text-emerald-400' : trend === 'down' ? 'text-red-400' : 'text-blue-400']">
                    {{ trend === 'up' ? '↑' : trend === 'down' ? '↓' : '•' }} {{ trendValue }}
                </span>
            </div>
            
            <div class="opacity-40 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 transform">
                <slot name="icon" />
            </div>
        </div>
    </div>
</template>
