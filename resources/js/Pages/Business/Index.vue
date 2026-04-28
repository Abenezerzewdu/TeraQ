<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Search, 
    MapPin, 
    Clock, 
    Users, 
    ChevronRight, 
    Building2,
    Filter,
    ArrowRight,
    Zap
} from 'lucide-vue-next';

const props = defineProps({
    businesses: Array,
    filters: Object,
});

const search = ref(props.filters.search);

// Simple custom debounce function
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const handleSearch = debounce((value) => {
    router.get(route("businesses"), { search: value }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}, 300);

watch(search, (value) => {
    handleSearch(value);
});

const getBusinessWaitTime = (business) => {
    if (!business.queues || business.queues.length === 0) return 0;
    const totalWait = business.queues.reduce((acc, q) => acc + (q.entries_count * q.avg_service_time), 0);
    return Math.round(totalWait / business.queues.length);
};

const getTotalWaiting = (business) => {
    if (!business.queues) return 0;
    return business.queues.reduce((acc, q) => acc + q.entries_count, 0);
};
</script>

<template>
    <Head title="Explore Businesses | TeraQ" />

    <div class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-24 selection:bg-teraq-primary/30">
        <!-- Top Nav -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-teraq-bg/60 backdrop-blur-xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teraq-primary to-teraq-secondary flex items-center justify-center shadow-lg shadow-teraq-primary/20">
                        <span class="text-white font-black text-xl">T</span>
                    </div>
                    <h1 class="text-white font-bold text-2xl tracking-tight">TeraQ</h1>
                </div>

                <div class="flex items-center gap-4">
                     <Link href="/" class="text-xs font-bold uppercase tracking-widest text-teraq-muted hover:text-white transition-colors">Home</Link>
                     <Link v-if="!$page.props.auth.user" :href="route('login')" class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-all">Log in</Link>
                     <div v-else class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5">
                        <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" class="w-full h-full object-cover rounded-[10px]" />
                     </div>
                </div>
            </div>
        </nav>

        <!-- Hero Search Section -->
        <section class="relative pt-40 pb-20 md:pt-48 md:pb-32 overflow-hidden">
            <!-- Background Visualization -->
            <div class="absolute inset-0 z-0">
                <img src="/images/explore_bg.png" class="w-full h-full object-cover opacity-30 scale-105" />
                <div class="absolute inset-0 bg-gradient-to-b from-teraq-bg/40 via-teraq-bg/80 to-teraq-bg"></div>
                <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-teraq-bg to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter text-white mb-6">
                    Find Your Next <span class="text-transparent bg-clip-text bg-gradient-to-r from-teraq-primary to-teraq-accent">Destination</span>
                </h1>
                <p class="text-teraq-muted text-lg font-medium mb-12 max-w-2xl mx-auto">
                    Explore top-rated businesses and join queues remotely. No more waiting in lines, just seamless experiences.
                </p>

                <!-- Premium Search Bar -->
                <div class="relative group max-w-2xl mx-auto">
                    <div class="absolute -inset-1 bg-gradient-to-r from-teraq-primary to-teraq-accent rounded-3xl blur opacity-20 group-focus-within:opacity-50 transition duration-500"></div>
                    <div class="relative flex items-center">
                        <Search class="absolute left-6 w-6 h-6 text-teraq-muted group-focus-within:text-teraq-primary transition-colors" />
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Search by name or location..." 
                            class="w-full bg-white/5 backdrop-blur-2xl border-2 border-white/5 rounded-3xl pl-16 pr-6 py-6 text-xl text-white focus:ring-teraq-primary/20 focus:border-teraq-primary/50 transition-all placeholder:text-teraq-muted/30"
                        />
                        <button class="absolute right-4 w-12 h-12 bg-teraq-primary rounded-2xl flex items-center justify-center hover:bg-teraq-secondary transition-colors shadow-lg shadow-teraq-primary/20">
                            <Filter class="w-5 h-5 text-white" />
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 space-y-12">
            <!-- Grid Header -->
            <div class="flex items-center justify-between border-b border-white/5 pb-6">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-black tracking-tight">Top Results</h2>
                    <span class="px-3 py-1 rounded-full bg-white/5 border border-white/5 text-[10px] font-bold text-teraq-muted uppercase tracking-widest">
                        {{ businesses.length }} Places
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-teraq-muted uppercase tracking-widest hidden sm:inline">Sort by</span>
                    <select class="bg-white/5 border-white/5 rounded-xl text-xs font-bold uppercase tracking-widest text-white px-4 py-2 focus:ring-teraq-primary/20 cursor-pointer">
                        <option>Popularity</option>
                        <option>Wait Time</option>
                        <option>Newest</option>
                    </select>
                </div>
            </div>

            <!-- Business Grid -->
            <div v-if="businesses.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="business in businesses" :key="business.id" class="group">
                    <div class="relative h-full glass-card hover:border-white/20 transition-all duration-500 flex flex-col overflow-hidden">
                        <!-- Card Header / Image Placeholder -->
                        <div class="relative h-48 overflow-hidden">
                            <img :src="business.hero_image_path ? `/storage/${business.hero_image_path}` : `https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=600&sig=${business.id}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div class="absolute inset-0 bg-gradient-to-t from-teraq-surface/90 to-transparent"></div>
                            
                            <!-- Status Badges -->
                            <div class="absolute top-4 left-4 flex gap-2">
                                <div class="px-3 py-1 rounded-lg bg-teraq-primary/20 backdrop-blur-md border border-teraq-primary/30 text-[10px] font-black uppercase tracking-widest">
                                    {{ business.queues?.length || 0 }} Queues
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-xl font-black tracking-tight text-white group-hover:text-teraq-primary transition-colors line-clamp-1">{{ business.name }}</h3>
                                    <div class="flex items-center gap-2 text-teraq-muted text-[10px] font-bold uppercase tracking-widest mt-1">
                                        <MapPin class="w-3 h-3 text-teraq-primary" />
                                        {{ business.location || 'Online' }}
                                    </div>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/5">
                                    <Building2 class="w-5 h-5 text-teraq-muted" />
                                </div>
                            </div>

                            <!-- Wait Stats -->
                            <div class="grid grid-cols-2 gap-4 py-4 border-y border-white/5 my-4">
                                <div>
                                    <p class="text-[9px] font-bold text-teraq-muted uppercase tracking-[0.2em] mb-1">Avg. Wait</p>
                                    <div class="flex items-center gap-1.5 font-black text-white">
                                        <Clock class="w-3.5 h-3.5 text-teraq-primary" />
                                        {{ getBusinessWaitTime(business) }}m
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-teraq-muted uppercase tracking-[0.2em] mb-1">Waiting Now</p>
                                    <div class="flex items-center gap-1.5 font-black text-white">
                                        <Users class="w-3.5 h-3.5 text-teraq-accent" />
                                        {{ getTotalWaiting(business) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Action -->
                            <div class="mt-auto pt-4 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Available</span>
                                </div>
                                <Link 
                                    :href="route('business.show', business.slug)" 
                                    class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teraq-primary hover:text-white hover:border-transparent transition-all flex items-center gap-2"
                                >
                                    Explore <ArrowRight class="w-3 h-3" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-20 text-center glass-card border-dashed border-white/10 bg-transparent flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6">
                    <Search class="w-8 h-8 text-teraq-muted" />
                </div>
                <h3 class="text-2xl font-black tracking-tight text-white mb-2">No Places Found</h3>
                <p class="text-teraq-muted max-w-sm mx-auto font-medium">We couldn't find any businesses matching "{{ search }}". Try a different search term or explore all businesses.</p>
                <button @click="search = ''" class="mt-8 px-8 py-3 bg-teraq-primary text-white rounded-xl font-bold uppercase tracking-widest text-[10px] hover:shadow-xl shadow-teraq-primary/20 transition-all">Clear Search</button>
            </div>
        </main>

        <!-- Premium Footer -->
        <footer class="mt-32 py-20 border-t border-white/5 bg-black/20">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <div class="flex flex-col items-center gap-8">
                     <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teraq-primary flex items-center justify-center">
                            <span class="text-white font-bold">T</span>
                        </div>
                        <h2 class="text-white font-bold text-xl tracking-tight">TeraQ</h2>
                    </div>
                    <p class="text-teraq-muted max-w-lg font-medium text-sm leading-relaxed">
                        Redefining how you spend your time. Discover places, join lines remotely, and live more. 
                        Your time is the only currency that matters.
                    </p>
                    <div class="flex items-center gap-12 text-[10px] font-bold uppercase tracking-[0.3em] text-white/40">
                        <a href="#" class="hover:text-teraq-primary transition-colors">Twitter</a>
                        <a href="#" class="hover:text-teraq-primary transition-colors">Instagram</a>
                        <a href="#" class="hover:text-teraq-primary transition-colors">Privacy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-teraq-surface/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] shadow-glass;
}
</style>
