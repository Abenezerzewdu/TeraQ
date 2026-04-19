<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    Search, 
    Bell, 
    Stethoscope, 
    UtensilsCrossed, 
    Scissors, 
    ShoppingBag, 
    Building2,
    Star,
    Clock,
    Home,
    MessageSquare,
    Compass,
    PlusCircle,
    ChevronRight
} from 'lucide-vue-next';
import BaseCard from '@/Components/BaseCard.vue';
import QueueButton from '@/Components/QueueButton.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const categories = [
    { name: 'Healthcare', icon: Stethoscope, color: 'bg-blue-500/10 text-blue-400' },
    { name: 'Dining', icon: UtensilsCrossed, color: 'bg-orange-500/10 text-orange-400' },
    { name: 'Salons', icon: Scissors, color: 'bg-purple-500/10 text-purple-400' },
    { name: 'Retail', icon: ShoppingBag, color: 'bg-emerald-500/10 text-emerald-400' },
];

const recommendations = [
    { 
        id: 1, 
        name: 'The Kinetic Grind', 
        rating: '4.9', 
        reviews: '120+', 
        wait: '8M', 
        status: 'LIVE', 
        statusColor: 'bg-blue-500',
        image: 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&q=80&w=400'
    },
    { 
        id: 2, 
        name: 'Studio Precision', 
        rating: '4.7', 
        reviews: '88', 
        wait: '25M', 
        status: 'BUSY', 
        statusColor: 'bg-orange-500',
        image: 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=400'
    },
    { 
        id: 3, 
        name: 'Onyx Dining Room', 
        rating: '5.0', 
        reviews: '210+', 
        wait: 'CLOSED', 
        status: 'QUEUE CLOSED', 
        statusColor: 'bg-teraq-muted',
        image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=400'
    },
];
</script>

<template>
    <Head title="Explore TeraQ" />

    <div class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-24">
        <!-- Top Nav -->
        <header class="h-20 border-b border-white/5 flex items-center justify-between px-6 sticky top-0 bg-teraq-bg/80 backdrop-blur-xl z-30">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teraq-primary to-teraq-secondary flex items-center justify-center shadow-lg shadow-teraq-primary/20">
                    <span class="text-white font-bold text-xl">T</span>
                </div>
                <h1 class="text-white font-bold text-xl tracking-tight">TeraQ</h1>
            </div>
            
            <div v-if="canLogin" class="flex items-center gap-4">
                <template v-if="$page.props.auth.user">
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5">
                        <Bell class="w-5 h-5 text-teraq-muted" />
                    </button>
                    <Link :href="route('dashboard')" class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" class="w-full h-full object-cover rounded-[10px]" />
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="text-xs font-bold uppercase tracking-widest text-teraq-muted hover:text-white transition-colors">Log in</Link>
                    <Link :href="route('register')" class="px-6 py-2.5 bg-teraq-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-teraq-secondary transition-all">Join</Link>
                </template>
            </div>
        </header>

        <main class="max-w-2xl mx-auto px-6 py-8 space-y-10">
            <!-- Search Section -->
            <div class="relative group">
                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted group-focus-within:text-teraq-primary transition-colors" />
                <input 
                    type="text" 
                    placeholder="Find a service or place..." 
                    class="w-full bg-white/5 border-teraq-border border-2 rounded-2xl pl-12 pr-6 py-5 text-lg text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/50"
                />
            </div>

            <!-- Active Spot Card (If logged in & in queue) -->
            <div v-if="$page.props.auth.user" class="relative group cursor-pointer hover:scale-[1.01] transition-all duration-300">
                <div class="absolute -inset-1 bg-gradient-to-r from-teraq-primary to-orange-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                <div class="relative glass-card bg-gradient-to-br from-teraq-primary/40 to-teraq-secondary/10 p-8 border-white/10 overflow-hidden">
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between">
                            <span class="text-white/60 text-[10px] font-bold uppercase tracking-[0.3em]">Live Queue Status</span>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-3xl font-bold text-white tracking-tight">Downtown Clinic</h3>
                                <p class="text-white/60 text-sm mt-1 font-medium">Estimated wait: 12 mins</p>
                            </div>
                            <div class="text-right">
                                <span class="text-5xl font-bold text-white tracking-tighter">#04</span>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mt-1">Your Spot</p>
                            </div>
                        </div>
                    </div>
                    <!-- Background aesthetic -->
                    <div class="absolute right-[-10%] bottom-[-10%] w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                </div>
            </div>

            <!-- Categories -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold tracking-tight">Popular Categories</h2>
                    <button class="text-[10px] font-bold uppercase tracking-widest text-teraq-muted hover:text-white transition-colors">View All</button>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div v-for="cat in categories" :key="cat.name" class="flex flex-col items-center gap-3 group cursor-pointer">
                        <div :class="[cat.color, 'w-16 h-16 rounded-2xl flex items-center justify-center border border-white/5 transition-all duration-300 group-hover:scale-110 group-active:scale-95 group-hover:border-white/20']">
                            <component :is="cat.icon" class="w-7 h-7" />
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-teraq-muted group-hover:text-white transition-colors">{{ cat.name }}</span>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="space-y-6">
                 <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold tracking-tight text-white/90">Recommended for You</h2>
                </div>
                <div class="space-y-4">
                    <div v-for="item in recommendations" :key="item.id" class="glass-card hover:bg-white/[0.04] transition-all p-3 border-white/5 group cursor-pointer flex gap-4">
                        <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0 relative">
                            <img :src="item.image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div v-if="item.status !== 'LIVE'" class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="text-[8px] font-bold text-white/80 uppercase tracking-widest">{{ item.status }}</span>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between py-1">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-white font-bold tracking-tight">{{ item.name }}</h4>
                                    <div class="flex items-center gap-1">
                                        <Star class="w-3 h-3 text-orange-400 fill-orange-400" />
                                        <span class="text-[10px] font-bold">{{ item.rating }}</span>
                                        <span class="text-[10px] text-teraq-muted">({{ item.reviews }})</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-3">
                                    <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-white/5 border border-white/5">
                                        <Clock class="w-3 h-3 text-teraq-muted" />
                                        <span class="text-[10px] font-bold uppercase" :class="[item.wait === 'CLOSED' ? 'text-orange-500' : 'text-white']">{{ item.wait }} WAIT</span>
                                    </div>
                                    <div v-if="item.status === 'LIVE'" class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <span class="text-[8px] font-bold uppercase tracking-widest text-emerald-500/80">Active</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button class="px-5 py-2 rounded-xl bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary text-[10px] font-bold uppercase tracking-widest hover:bg-teraq-primary hover:text-white transition-all">
                                    {{ item.wait === 'CLOSED' ? 'BOOK' : 'JOIN' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Tab Nav (Mobile-ish feel) -->
        <nav class="fixed bottom-0 left-0 right-0 h-24 bg-teraq-bg/90 backdrop-blur-2xl border-t border-white/5 flex items-center justify-around px-4 z-40">
            <button class="flex flex-col items-center gap-1 text-teraq-primary">
                <div class="w-12 h-12 rounded-xl bg-teraq-primary/10 flex items-center justify-center">
                    <Home class="w-6 h-6" />
                </div>
                <span class="text-[9px] font-bold uppercase tracking-widest">Home</span>
            </button>
            <button class="flex flex-col items-center gap-1 text-teraq-muted hover:text-white transition-all">
                <Search class="w-6 h-6" />
                <span class="text-[9px] font-bold uppercase tracking-widest">Search</span>
            </button>
            <button class="flex flex-col items-center gap-1 text-teraq-muted hover:text-white transition-all">
                 <Compass class="w-6 h-6" />
                <span class="text-[9px] font-bold uppercase tracking-widest">Queues</span>
            </button>
            <button class="flex flex-col items-center gap-1 text-teraq-muted hover:text-white transition-all">
                <PlusCircle class="w-6 h-6" />
                <span class="text-[9px] font-bold uppercase tracking-widest">Create</span>
            </button>
        </nav>
    </div>
</template>
