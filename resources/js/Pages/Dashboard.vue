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
    Wifi,
    Coffee,
    Monitor,
    Zap,
    Wind,
    ChevronRight,
    Circle,
    PlusSquare
} from 'lucide-vue-next';

const props = defineProps({
    active_entries: Array,
    owned_businesses: Array,
});

const activities = [
    { id: 1, name: 'Marcus Chen', action: 'Joined the queue', time: 'JUST NOW', avatar: 'https://i.pravatar.cc/150?u=marcus' },
    { id: 2, name: 'Sarah Jenkins', action: 'Now being served', time: '2M AGO', avatar: 'https://i.pravatar.cc/150?u=sarah' },
    { id: 3, name: 'David Miller', action: 'Joined the queue', time: '5M AGO', avatar: 'https://i.pravatar.cc/150?u=david' },
    { id: 4, name: 'Elena Rodriguez', action: 'Left the queue', time: '12M AGO', avatar: 'https://i.pravatar.cc/150?u=elena' },
];

const amenities = [
    { name: 'High-Speed WiFi', icon: Wifi },
    { name: 'Premium Coffee', icon: Coffee },
    { name: 'Workstations', icon: Monitor },
    { name: 'EV Charging', icon: Zap },
    { name: 'Climate Control', icon: Wind },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Kinetic Dashboard</h1>
                    <p class="text-teraq-muted mt-1">Manage your businesses and track your active queues.</p>
                </div>
                <div class="flex items-center gap-3">
                    <QueueButton v-if="!owned_businesses.length" :href="route('businesses.create')">
                        REGISTER BUSINESS
                    </QueueButton>
                </div>
            </div>

            <!-- Business Owner Section (if they have businesses) -->
            <div v-if="owned_businesses.length" class="space-y-4">
                <h3 class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">Your Businesses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <BaseCard v-for="business in owned_businesses" :key="business.id" :title="business.name" :subtitle="`${business.queues_count} Active Queues`" gradient>
                        <template #action>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
                                <Circle class="w-2 h-2 fill-current" />
                                Operational
                            </div>
                        </template>

                        <div class="mt-4">
                             <Link :href="route('business.show', business.slug)" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-all">
                                MANAGE CONSOLE
                                <ChevronRight class="w-4 h-4" />
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
            <div class="space-y-4">
                <h3 class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">Your Active Queues</h3>
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

            <!-- Third Row: Amenities -->
            <div class="space-y-4">
                <h3 class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">On-Site Amenities</h3>
                <div class="flex flex-wrap gap-4">
                    <div v-for="amenity in amenities" :key="amenity.name" class="glass-card flex items-center gap-4 px-6 py-4 hover:border-teraq-primary/30 transition-all duration-300 group cursor-default">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center transition-colors group-hover:bg-teraq-primary/20">
                            <component :is="amenity.icon" class="w-5 h-5 text-teraq-muted transition-colors group-hover:text-teraq-primary" />
                        </div>
                        <span class="text-white font-semibold">{{ amenity.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
