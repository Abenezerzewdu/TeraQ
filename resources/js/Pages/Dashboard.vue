<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
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
    Circle
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
            <!-- Hero Stats Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Current Queue Status Card -->
                <div class="lg:col-span-2">
                    <BaseCard title="CURRENT QUEUE STATUS" gradient>
                        <template #action>
                            <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold uppercase tracking-wider">
                                <Zap class="w-3 h-3 fill-current" />
                                Moving Fast
                            </div>
                        </template>

                        <div class="flex items-end gap-3 my-4">
                            <h2 class="text-[120px] font-bold leading-[0.9] tracking-tighter text-white">15</h2>
                            <span class="text-4xl font-medium text-teraq-muted pb-4">mins</span>
                        </div>

                        <p class="text-teraq-muted text-lg leading-relaxed max-w-xl">
                            The current estimated wait time for premium services. Our kinetic processing engine is operating at peak efficiency.
                        </p>

                        <template #footer>
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                                <QueueButton class="w-full sm:w-auto px-12 py-5 text-lg" showArrow>
                                    JOIN QUEUE
                                </QueueButton>
                                
                                <div class="flex items-center gap-4 text-right">
                                    <div>
                                        <p class="text-white font-bold text-xl leading-tight">12 people</p>
                                        <p class="text-teraq-muted text-sm">currently in line</p>
                                    </div>
                                    <div class="flex -space-x-3">
                                        <img v-for="i in 4" :key="i" :src="`https://i.pravatar.cc/150?u=${i}`" class="w-10 h-10 rounded-full border-2 border-teraq-surface" />
                                        <div class="w-10 h-10 rounded-full border-2 border-teraq-surface bg-teraq-border flex items-center justify-center text-[10px] font-bold text-white">
                                            +8
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </BaseCard>
                </div>

                <!-- Live Activity Card -->
                <BaseCard title="Live Activity" subtitle="Real-time Updates">
                    <div class="space-y-4">
                        <div v-for="item in activities" :key="item.id" class="flex items-center gap-4 group cursor-pointer">
                            <div class="relative">
                                <img :src="item.avatar" class="w-12 h-12 rounded-xl transition-transform duration-300 group-hover:scale-105" />
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-teraq-surface flex items-center justify-center" 
                                     :class="[item.action.includes('Joined') ? 'bg-blue-500' : item.action.includes('served') ? 'bg-orange-500' : 'bg-teraq-muted']">
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white font-semibold text-sm">{{ item.name }}</h4>
                                <p class="text-teraq-muted text-xs">{{ item.action }}</p>
                            </div>
                            <span class="text-teraq-muted text-[10px] font-bold">{{ item.time }}</span>
                        </div>
                    </div>
                </BaseCard>
            </div>

            <!-- Second Row: Hub & Hours -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Location Hub Card -->
                <BaseCard noPadding class="relative min-h-[400px]">
                    <!-- Map Placeholder with dark theme aesthetic -->
                    <div class="absolute inset-0 bg-[#0d0f14] flex items-center justify-center opacity-40">
                         <div class="w-full h-full bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    </div>
                    
                    <div class="absolute inset-x-6 bottom-6">
                        <div class="glass-card p-6 bg-teraq-surface/80 backdrop-blur-2xl border-white/10 flex items-center justify-between">
                            <div>
                                <h3 class="text-white text-2xl font-bold">Downtown Hub</h3>
                                <div class="flex items-center gap-2 text-teraq-muted mt-2 text-sm">
                                    <MapPin class="w-4 h-4" />
                                    <span>450 Mission St, San Francisco, CA 94105</span>
                                </div>
                            </div>
                            <QueueButton variant="secondary" class="flex items-center gap-2">
                                <Navigation class="w-4 h-4" />
                                GET DIRECTIONS
                            </QueueButton>
                        </div>
                    </div>
                </BaseCard>

                <!-- Operational Hours Card -->
                <BaseCard>
                    <template #header>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                                <Clock class="w-5 h-5 text-orange-500" />
                            </div>
                            <h3 class="text-white text-xl font-bold">Operational Hours</h3>
                        </div>
                    </template>

                    <div class="space-y-6 mt-4">
                        <div class="flex items-center justify-between py-2 border-b border-white/5">
                            <span class="text-teraq-muted font-medium">Mon - Fri</span>
                            <span class="text-white font-bold tracking-wider">08:00 - 20:00</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-white/5 text-teraq-accent">
                            <span class="font-medium">Saturday</span>
                            <span class="font-bold tracking-wider">09:00 - 18:00</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-teraq-muted font-medium">Sunday</span>
                            <span class="text-orange-500 font-bold tracking-wider">Closed</span>
                        </div>

                        <div class="mt-8 p-4 rounded-2xl bg-teraq-surface border border-white/5 flex items-center justify-between group cursor-pointer hover:border-emerald-500/30 transition-all duration-500">
                             <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                <span class="text-white font-bold">Currently Open</span>
                            </div>
                            <ChevronRight class="w-4 h-4 text-teraq-muted group-hover:translate-x-1 transition-transform" />
                        </div>
                    </div>
                </BaseCard>
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
