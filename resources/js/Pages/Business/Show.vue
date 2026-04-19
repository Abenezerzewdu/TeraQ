<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { 
    Plus, 
    Building2, 
    MapPin, 
    Users, 
    Clock, 
    Zap, 
    ChevronRight,
    Search,
    ChevronLeft
} from "lucide-vue-next";
import BaseCard from '@/Components/BaseCard.vue';
import StatCard from '@/Components/StatCard.vue';

const props = defineProps({
    business: Object,
});

const form = useForm({
    name: "",
    avg_service_time: 10,
});

const submitQueue = () => {
    form.post(`/businesses/${props.business.slug}/queues`, {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head :title="business.name" />

    <div class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-24 selection:bg-teraq-primary/30">
        <!-- Top Nav -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-teraq-bg/60 backdrop-blur-xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link href="/" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all group">
                        <ChevronLeft class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teraq-primary to-teraq-secondary flex items-center justify-center">
                            <span class="text-white font-bold text-xs">T</span>
                        </div>
                        <span class="font-bold text-lg tracking-tight">TeraQ</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 border border-white/5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-500">Live Status</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5">
                        <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" class="w-full h-full object-cover rounded-[10px]" />
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-32 space-y-12">
            <!-- Business Hero -->
            <div class="relative rounded-[2.5rem] overflow-hidden group">
                <!-- Background Image & Gradient -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-teraq-bg via-teraq-bg/40 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-teraq-bg/80 via-transparent to-transparent"></div>
                </div>

                <div class="relative z-10 p-8 md:p-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teraq-primary/20 backdrop-blur-md border border-teraq-primary/30 text-white text-xs font-bold uppercase tracking-widest">
                            <Building2 class="w-4 h-4" />
                            Official Business Profile
                        </div>
                        <div>
                            <h1 class="text-5xl md:text-7xl font-black tracking-tighter text-white mb-4">{{ business.name }}</h1>
                            <div class="flex items-center gap-3 text-teraq-muted">
                                <MapPin class="w-5 h-5 text-teraq-primary" />
                                <span class="text-lg font-medium">{{ business.location || 'Location not specified' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="glass-card px-8 py-6 bg-white/5 backdrop-blur-2xl border-white/10 flex flex-col items-center justify-center min-w-[140px]">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted mb-2">Total Queues</span>
                            <span class="text-4xl font-black text-white">{{ business.queues?.length || 0 }}</span>
                        </div>
                        <div class="glass-card px-8 py-6 bg-teraq-primary/10 backdrop-blur-2xl border-teraq-primary/20 flex flex-col items-center justify-center min-w-[140px]">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-primary mb-2">Wait Level</span>
                            <span class="text-2xl font-black text-white">LOW</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Queues List (Left) -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-black tracking-tight flex items-center gap-3">
                            <Zap class="w-6 h-6 text-teraq-primary fill-teraq-primary" />
                            Active Queues
                        </h2>
                        <div class="relative group">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted group-focus-within:text-teraq-primary transition-colors" />
                            <input 
                                type="text" 
                                placeholder="Filter queues..." 
                                class="bg-white/5 border-white/5 rounded-xl pl-10 pr-4 py-2 text-sm text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all w-48"
                            />
                        </div>
                    </div>

                    <div v-if="business.queues?.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="queue in business.queues" :key="queue.id" class="group relative">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-teraq-primary to-teraq-accent rounded-3xl blur opacity-0 group-hover:opacity-20 transition duration-500"></div>
                            <div class="relative glass-card p-6 bg-white/[0.03] border-white/5 hover:border-white/10 transition-all flex flex-col h-full">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-12 h-12 rounded-2xl bg-teraq-primary/10 flex items-center justify-center text-teraq-primary">
                                        <Users class="w-6 h-6" />
                                    </div>
                                    <div class="px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-bold uppercase tracking-wider">
                                        Live
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ queue.name }}</h3>
                                <p class="text-teraq-muted text-sm flex items-center gap-2 mb-8">
                                    <Clock class="w-4 h-4" />
                                    {{ queue.avg_service_time }} min avg. wait
                                </p>
                                <div class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest mb-1">Queue ID</p>
                                        <p class="text-xs font-mono text-white/60">#{{ queue.id.toString().padStart(4, '0') }}</p>
                                    </div>
                                    <Link :href="`/queues/${queue.slug}`" class="px-5 py-2.5 bg-teraq-primary/10 border border-teraq-primary/20 rounded-xl text-teraq-primary text-xs font-bold uppercase tracking-widest hover:bg-teraq-primary hover:text-white transition-all flex items-center gap-2">
                                        Open <ArrowRight class="w-3 h-3" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="glass-card p-12 text-center border-dashed border-white/10 bg-transparent">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                            <Plus class="w-8 h-8 text-teraq-muted" />
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">No Queues Yet</h4>
                        <p class="text-teraq-muted max-w-xs mx-auto text-sm">Create your first queue to start managing customer flow.</p>
                    </div>
                </div>

                <!-- Create Queue (Right) -->
                <div class="space-y-8">
                    <h2 class="text-2xl font-black tracking-tight">Create Queue</h2>
                    <div class="glass-card p-8 bg-gradient-to-br from-teraq-primary/10 to-transparent border-teraq-primary/20">
                        <form @submit.prevent="submitQueue" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted pl-1">Queue Name</label>
                                <input 
                                    v-model="form.name" 
                                    placeholder="e.g. Appointment Line" 
                                    class="w-full bg-teraq-bg/50 border-white/10 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/50"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted pl-1">Avg. Service Time (min)</label>
                                <input 
                                    v-model="form.avg_service_time" 
                                    type="number" 
                                    class="w-full bg-teraq-bg/50 border-white/10 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all"
                                    required
                                />
                            </div>
                            <button 
                                type="submit" 
                                class="w-full py-4 bg-teraq-primary text-white rounded-xl font-bold uppercase tracking-widest hover:bg-teraq-secondary hover:-translate-y-1 shadow-lg shadow-teraq-primary/20 transition-all flex items-center justify-center gap-2"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Creating...</span>
                                <template v-else>
                                    Create Queue <Plus class="w-5 h-5" />
                                </template>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-teraq-surface/40 backdrop-blur-xl border border-white/5 rounded-3xl shadow-glass;
}
</style>
