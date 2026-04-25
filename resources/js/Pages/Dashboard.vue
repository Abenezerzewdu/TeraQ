<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import BaseCard from '@/Components/BaseCard.vue';
import StatCard from '@/Components/StatCard.vue';
import QueueButton from '@/Components/QueueButton.vue';
import { 
    Users, 
    Clock, 
    TrendingUp, 
    MapPin, 
    Navigation,
    Zap,
    ChevronRight,
    Circle,
    PlusSquare,
    Settings
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    active_entries: Array,
    owned_businesses: Array,
});

const activeTab = ref(props.owned_businesses.length > 0 ? 'businesses' : 'queues');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">TeraQ Dashboard</h1>
                    <p class="text-teraq-muted mt-1">Manage your professional presence and track your active queues.</p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="flex p-1 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-xl">
                    <button 
                        @click="activeTab = 'businesses'"
                        class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-300 flex items-center gap-2"
                        :class="activeTab === 'businesses' ? 'bg-teraq-primary text-white shadow-lg shadow-teraq-primary/20' : 'text-teraq-muted hover:text-white'"
                    >
                        <PlusSquare class="w-4 h-4" />
                        My Businesses
                    </button>
                    <button 
                        @click="activeTab = 'queues'"
                        class="px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-300 flex items-center gap-2"
                        :class="activeTab === 'queues' ? 'bg-teraq-primary text-white shadow-lg shadow-teraq-primary/20' : 'text-teraq-muted hover:text-white'"
                    >
                        <Clock class="w-4 h-4" />
                        My Queues
                        <span v-if="active_entries.length" class="ml-1 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">
                            {{ active_entries.length }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Business Owner Section -->
            <div v-if="activeTab === 'businesses'" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex items-center justify-between">
                    <h3 class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">Managed Entities</h3>
                    <QueueButton :href="route('businesses.create')" class="scale-90 origin-right">
                        REGISTER NEW
                    </QueueButton>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <BaseCard v-for="business in owned_businesses" :key="business.id" :title="business.name" :subtitle="`${business.queues_count} Active Queues`" gradient>
                        <template #icon>
                            <img v-if="business.logo_path" :src="`/storage/${business.logo_path}`" class="w-6 h-6 object-contain rounded-md bg-white/10" />
                        </template>
                        <template #action>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
                                <Circle class="w-2 h-2 fill-current" />
                                Operational
                            </div>
                        </template>

                        <div class="mt-4 flex gap-2">
                             <Link :href="route('business.show', business.slug)" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-all">
                                MANAGE CONSOLE
                                <ChevronRight class="w-4 h-4" />
                            </Link>
                            <Link :href="route('businesses.edit', business.slug)" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-teraq-muted hover:text-white hover:bg-white/10 transition-all" title="Business Settings">
                                <Settings class="w-5 h-5" />
                            </Link>
                        </div>
                    </BaseCard>

                    <!-- Add Another Business -->
                    <Link :href="route('businesses.create')" class="flex flex-col items-center justify-center gap-4 p-8 rounded-3xl border-2 border-dashed border-white/5 hover:border-teraq-primary/30 hover:bg-teraq-primary/5 transition-all group">
                        <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <PlusSquare class="w-6 h-6 text-teraq-muted group-hover:text-teraq-primary" />
                        </div>
                        <span class="text-teraq-muted font-bold group-hover:text-white">Add New Business</span>
                    </Link>
                </div>
            </div>

            <!-- Customer Persona: Active Queues -->
            <div v-if="activeTab === 'queues'" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <h3 class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">Current Assignments</h3>
                <div v-if="active_entries.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                     <BaseCard v-for="entry in active_entries" :key="entry.id" :title="entry.queue.business.name" :subtitle="entry.queue.name" :gradient="entry.status === 'serving'">
                        <template #action>
                            <div v-if="entry.status === 'serving'" class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold uppercase tracking-wider animate-pulse">
                                <Zap class="w-3 h-3 fill-current" />
                                IT'S YOUR TURN
                            </div>
                            <div v-else class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-teraq-muted text-[10px] font-bold uppercase tracking-wider">
                                <Clock class="w-3 h-3" />
                                WAITING
                            </div>
                        </template>

                        <div class="flex flex-col items-center justify-center py-6">
                             <p class="text-teraq-muted text-[10px] uppercase tracking-widest font-bold mb-1">YOUR POSITION</p>
                             <h2 class="text-6xl font-bold text-white tracking-tighter" :class="{'text-orange-500': entry.status === 'serving'}">
                                {{ entry.status === 'serving' ? 'NOW' : '#' + entry.position }}
                             </h2>
                        </div>

                        <template #footer>
                            <Link :href="`/queues/${entry.queue.slug}`" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-teraq-primary text-white font-bold hover:bg-opacity-90 transition-all shadow-lg shadow-teraq-primary/20">
                                VIEW LIVE STATUS
                                <ChevronRight class="w-4 h-4" />
                            </Link>
                        </template>
                    </BaseCard>
                </div>
                
                <div v-else class="glass-card p-12 flex flex-col items-center justify-center text-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center">
                        <TrendingUp class="w-8 h-8 text-teraq-muted/30" />
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-lg">No active queues</h4>
                        <p class="text-teraq-muted max-w-sm mx-auto mt-1">You aren't in any queues right now. Explore local businesses to join a line.</p>
                    </div>
                    <Link :href="route('home')" class="mt-2 px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-all">
                        EXPLORE SERVICES
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
