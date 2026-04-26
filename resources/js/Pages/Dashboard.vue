<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
    Users, 
    Clock, 
    TrendingUp, 
    MapPin, 
    Zap,
    ChevronRight,
    Plus,
    Settings,
    Building2,
    ArrowUpRight,
    Circle,
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    active_entries: Array,
    owned_businesses: Array,
});

const { props: pageProps } = usePage();
const activeTab = ref(props.owned_businesses.length > 0 ? 'businesses' : 'queues');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Greeting -->
        <div class="mb-10">
            <p class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold mb-1">Welcome back</p>
            <h1 class="text-white text-4xl font-black tracking-tight">
                {{ pageProps.auth.user.name.split(' ')[0] }}<span class="text-teraq-muted">.</span>
            </h1>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="glass-card p-5">
                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-3">Businesses</p>
                <p class="text-4xl font-black text-white">{{ owned_businesses.length }}</p>
            </div>
            <div class="glass-card p-5">
                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-3">Total Queues</p>
                <p class="text-4xl font-black text-white">{{ owned_businesses.reduce((a, b) => a + b.queues_count, 0) }}</p>
            </div>
            <div class="glass-card p-5">
                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-3">Active Joins</p>
                <p class="text-4xl font-black text-white">{{ active_entries.length }}</p>
            </div>
            <div class="glass-card p-5 bg-teraq-primary/5 border-teraq-primary/20">
                <p class="text-teraq-primary text-[10px] uppercase tracking-[0.2em] font-bold mb-3">Status</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    <p class="text-white font-bold text-sm">Operational</p>
                </div>
            </div>
        </div>

        <!-- Tab Nav -->
        <div class="flex items-center gap-1 mb-8 border-b border-white/5 pb-0">
            <button
                @click="activeTab = 'businesses'"
                class="px-5 py-3 text-xs font-bold uppercase tracking-widest transition-all border-b-2 -mb-px"
                :class="activeTab === 'businesses'
                    ? 'text-white border-teraq-primary'
                    : 'text-teraq-muted border-transparent hover:text-white'"
            >
                My Businesses
                <span class="ml-2 px-1.5 py-0.5 rounded-md text-[10px]"
                    :class="activeTab === 'businesses' ? 'bg-teraq-primary/20 text-teraq-primary' : 'bg-white/5 text-teraq-muted'">
                    {{ owned_businesses.length }}
                </span>
            </button>
            <button
                @click="activeTab = 'queues'"
                class="px-5 py-3 text-xs font-bold uppercase tracking-widest transition-all border-b-2 -mb-px"
                :class="activeTab === 'queues'
                    ? 'text-white border-teraq-primary'
                    : 'text-teraq-muted border-transparent hover:text-white'"
            >
                My Queues
                <span v-if="active_entries.length" class="ml-2 px-1.5 py-0.5 rounded-md text-[10px]"
                    :class="activeTab === 'queues' ? 'bg-teraq-primary/20 text-teraq-primary' : 'bg-white/5 text-teraq-muted'">
                    {{ active_entries.length }}
                </span>
            </button>
        </div>

        <!-- Businesses Tab -->
        <div v-if="activeTab === 'businesses'">
            <div class="flex items-center justify-between mb-6">
                <p class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold">Managed Entities</p>
                <Link :href="route('businesses.create')" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary text-xs font-bold uppercase tracking-widest hover:bg-teraq-primary hover:text-white transition-all">
                    <Plus class="w-3.5 h-3.5" />
                    Register New
                </Link>
            </div>

            <!-- Business Cards -->
            <div v-if="owned_businesses.length" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <div v-for="business in owned_businesses" :key="business.id" class="group glass-card overflow-hidden flex flex-col">
                    <!-- Hero Image -->
                    <div class="relative h-32 bg-teraq-surface overflow-hidden shrink-0">
                        <img
                            v-if="business.hero_image_path"
                            :src="`/storage/${business.hero_image_path}`"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teraq-primary/10 to-teraq-surface">
                            <Building2 class="w-10 h-10 text-teraq-primary/30" />
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-teraq-surface via-transparent to-transparent"></div>

                        <!-- Logo -->
                        <div class="absolute bottom-3 left-4">
                            <div v-if="business.logo_path" class="w-10 h-10 rounded-xl bg-teraq-bg/80 backdrop-blur border border-white/10 p-1 shadow-lg">
                                <img :src="`/storage/${business.logo_path}`" class="w-full h-full object-contain rounded-lg" />
                            </div>
                            <div v-else class="w-10 h-10 rounded-xl bg-teraq-primary/20 border border-teraq-primary/20 flex items-center justify-center">
                                <Building2 class="w-5 h-5 text-teraq-primary" />
                            </div>
                        </div>

                        <!-- Status badge -->
                        <div class="absolute top-3 right-3 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-sm">
                            <Circle class="w-1.5 h-1.5 fill-emerald-500 text-emerald-500" />
                            <span class="text-emerald-400 text-[9px] font-bold uppercase tracking-wider">Live</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-white font-bold text-lg leading-tight mb-1">{{ business.name }}</h3>
                        <div v-if="business.location" class="flex items-center gap-1.5 text-teraq-muted text-xs mb-4">
                            <MapPin class="w-3 h-3 shrink-0" />
                            <span class="truncate">{{ business.location }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-auto pt-4 border-t border-white/5">
                            <div class="flex-1 text-center">
                                <p class="text-[10px] text-teraq-muted uppercase tracking-widest font-bold">Queues</p>
                                <p class="text-white font-black text-xl">{{ business.queues_count }}</p>
                            </div>
                            <div class="flex gap-2 flex-1 justify-end">
                                <Link :href="route('business.show', business.slug)" class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary text-[10px] font-bold uppercase tracking-widest hover:bg-teraq-primary hover:text-white transition-all">
                                    Manage
                                    <ArrowUpRight class="w-3 h-3" />
                                </Link>
                                <Link :href="route('businesses.edit', business.slug)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-teraq-muted hover:text-white hover:bg-white/10 transition-all">
                                    <Settings class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add New -->
                <Link :href="route('businesses.create')" class="flex flex-col items-center justify-center gap-3 p-8 rounded-2xl border-2 border-dashed border-white/5 hover:border-teraq-primary/30 hover:bg-teraq-primary/5 transition-all group min-h-[220px]">
                    <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center group-hover:scale-110 group-hover:bg-teraq-primary/10 transition-all">
                        <Plus class="w-6 h-6 text-teraq-muted group-hover:text-teraq-primary" />
                    </div>
                    <span class="text-teraq-muted text-xs font-bold uppercase tracking-widest group-hover:text-white transition-colors">Add Business</span>
                </Link>
            </div>

            <!-- Empty state -->
            <div v-else class="glass-card p-16 flex flex-col items-center justify-center text-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-teraq-primary/10 border border-teraq-primary/20 flex items-center justify-center">
                    <Building2 class="w-10 h-10 text-teraq-primary/60" />
                </div>
                <div>
                    <h4 class="text-white font-bold text-xl mb-2">No businesses yet</h4>
                    <p class="text-teraq-muted max-w-xs mx-auto text-sm">Register your first business to start managing queues and serving customers.</p>
                </div>
                <Link :href="route('businesses.create')" class="mt-2 px-8 py-3 rounded-xl bg-teraq-primary text-white font-bold text-xs uppercase tracking-widest hover:bg-teraq-secondary transition-all shadow-lg shadow-teraq-primary/20">
                    Register Business
                </Link>
            </div>
        </div>

        <!-- Queues Tab -->
        <div v-if="activeTab === 'queues'">
            <p class="text-teraq-muted text-xs uppercase tracking-[0.2em] font-bold mb-6">Current Assignments</p>

            <div v-if="active_entries.length" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <div v-for="entry in active_entries" :key="entry.id" class="glass-card overflow-hidden flex flex-col"
                    :class="entry.status === 'serving' ? 'border-orange-500/20' : ''">

                    <!-- Status bar -->
                    <div class="h-1 w-full" :class="entry.status === 'serving' ? 'bg-gradient-to-r from-orange-500 to-amber-400' : 'bg-teraq-primary/30'"></div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="text-white font-bold">{{ entry.queue.business.name }}</p>
                                <p class="text-teraq-muted text-xs mt-0.5">{{ entry.queue.name }}</p>
                            </div>
                            <div v-if="entry.status === 'serving'" class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[9px] font-bold uppercase tracking-wider animate-pulse">
                                <Zap class="w-3 h-3 fill-current" />
                                Your Turn
                            </div>
                            <div v-else class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-teraq-muted text-[9px] font-bold uppercase tracking-wider">
                                <Clock class="w-3 h-3" />
                                Waiting
                            </div>
                        </div>

                        <div class="flex items-center justify-center py-4">
                            <div class="text-center">
                                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-1">Position</p>
                                <p class="text-6xl font-black tracking-tighter" :class="entry.status === 'serving' ? 'text-orange-400' : 'text-white'">
                                    {{ entry.status === 'serving' ? 'NOW' : '#' + entry.position }}
                                </p>
                            </div>
                        </div>

                        <Link :href="`/queues/${entry.queue.slug}`" class="mt-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all"
                            :class="entry.status === 'serving'
                                ? 'bg-orange-500 text-white hover:bg-orange-400 shadow-lg shadow-orange-500/20'
                                : 'bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary hover:bg-teraq-primary hover:text-white'">
                            View Live Status
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <div v-else class="glass-card p-16 flex flex-col items-center justify-center text-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-white/5 flex items-center justify-center">
                    <TrendingUp class="w-10 h-10 text-teraq-muted/30" />
                </div>
                <div>
                    <h4 class="text-white font-bold text-xl mb-2">No active queues</h4>
                    <p class="text-teraq-muted max-w-sm mx-auto text-sm">You aren't in any queues right now. Explore local businesses to join a line.</p>
                </div>
                <Link :href="route('home')" class="mt-2 px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold text-xs uppercase tracking-widest hover:bg-white/10 transition-all">
                    Explore Services
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
