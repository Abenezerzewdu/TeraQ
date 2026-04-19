<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import { 
    ChevronLeft, 
    Bell, 
    Zap, 
    Users, 
    MapPin, 
    Clock, 
    Wifi, 
    Coffee, 
    ParkingCircle, 
    Accessibility, 
    CloudSun,
    ArrowRight
} from "lucide-vue-next";
import BaseCard from '@/Components/BaseCard.vue';
import QueueButton from '@/Components/QueueButton.vue';

const props = defineProps({
    queue: Object,
    entries: Array,
});

// ✅ Clone initial data
const entries = ref([...props.entries]);

let channel = null;

onMounted(() => {
    if (window.Echo) {
        channel = window.Echo.channel(`queue.${props.queue.id}`).listen(
            ".user.joined",
            (e) => {
                const exists = entries.value.find((entry) => entry.id === e.entry.id);
                if (!exists) {
                    entries.value = [e.entry, ...entries.value];
                }
            },
        );
    }
});

onUnmounted(() => {
    if (channel) {
        channel.stopListening(".user.joined");
    }
});

const form = useForm({
    name: "",
});

const join = () => {
    form.post(`/queues/${props.queue.slug}/join`, {
        preserveScroll: true,
        preserveState: true,
    });
};

const amenities = [
    { name: 'Free WiFi', icon: Wifi },
    { name: 'Valet Parking', icon: ParkingCircle },
    { name: 'Accessible', icon: Accessibility },
    { name: 'Outdoor Seating', icon: CloudSun },
];
</script>

<template>
    <Head :title="queue.name" />

    <div class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-12">
        <!-- Persistent Header for Mobile-view feel -->
        <header class="h-20 border-b border-white/5 flex items-center justify-between px-6 sticky top-0 bg-teraq-bg/80 backdrop-blur-xl z-20">
            <div class="flex items-center gap-4">
                <Link href="/" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all">
                    <ChevronLeft class="w-5 h-5" />
                </Link>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teraq-primary to-teraq-secondary flex items-center justify-center">
                        <span class="text-white font-bold text-xs">T</span>
                    </div>
                    <span class="font-bold text-lg tracking-tight">TeraQ</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5">
                    <Bell class="w-5 h-5 text-teraq-muted" />
                </button>
                <div class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" class="w-full h-full object-cover rounded-[10px]" shadow-lg />
                </div>
            </div>
        </header>

        <main class="max-w-2xl mx-auto px-6 py-8 space-y-8">
            <!-- Business Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold uppercase tracking-[0.2em] w-fit">
                    <Coffee class="w-3 h-3 fill-current" />
                    Premium Dining
                </div>
                <div>
                    <h1 class="text-white text-5xl font-bold tracking-tight">{{ queue.name }}</h1>
                    <p class="text-teraq-muted mt-4 text-lg leading-relaxed">
                        Elevated coastal cuisine with a focus on sustainable seafood and artisanal cocktails. Experience the ocean's finest flavors in a meticulously curated atmosphere.
                    </p>
                </div>
            </div>

            <!-- Wait Time Big Card -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-teraq-primary to-teraq-accent rounded-[2rem] blur opacity-25 group-hover:opacity-40 transition duration-1000"></div>
                <div class="relative glass-card bg-gradient-to-br from-teraq-primary/40 to-teraq-secondary/20 p-12 flex flex-col items-center justify-center overflow-hidden min-h-[300px]">
                    <span class="text-white/60 text-xs font-bold uppercase tracking-[0.3em] mb-6">Current Wait</span>
                    <div class="flex items-end gap-3">
                        <h2 class="text-9xl font-bold tracking-tighter text-white drop-shadow-2xl">15</h2>
                        <span class="text-4xl font-medium text-white/50 pb-6 uppercase">Mins</span>
                    </div>
                    <!-- Decorative circles -->
                    <div class="absolute top-[-20%] left-[-10%] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-[-20%] right-[-10%] w-64 h-64 bg-teraq-primary/20 rounded-full blur-3xl"></div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="glass-card p-6 border-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-teraq-muted text-[10px] font-bold uppercase tracking-widest">People Ahead</span>
                        <Users class="w-4 h-4 text-teraq-muted" />
                    </div>
                    <div class="text-3xl font-bold text-white">4</div>
                </div>
                <div class="glass-card p-6 border-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-teraq-muted text-[10px] font-bold uppercase tracking-widest">Status</span>
                        <Zap class="w-4 h-4 text-orange-400 fill-orange-400" />
                    </div>
                    <div class="text-xl font-bold text-white uppercase tracking-wider">Moving Fast</div>
                </div>
            </div>

            <!-- Join Queue Action -->
            <div class="space-y-4 pt-4">
                <form @submit.prevent="join" class="space-y-4">
                    <div class="relative">
                        <input 
                            v-model="form.name" 
                            placeholder="Enter your name to join" 
                            class="w-full bg-teraq-surface border-teraq-border border-2 rounded-2xl px-6 py-5 text-lg text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/30"
                            required
                        />
                        <div v-if="form.errors.name" class="text-red-400 text-xs mt-2 pl-2 font-medium">{{ form.errors.name }}</div>
                    </div>
                    <QueueButton type="submit" class="w-full py-6 text-xl" showArrow :loading="form.processing">
                        JOIN QUEUE
                    </QueueButton>
                </form>
            </div>

            <!-- Details Section -->
            <BaseCard noPadding>
                <!-- Location Map Placeholder -->
                <div class="h-48 bg-teraq-surface border-b border-white/5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[#0d0f14] opacity-40">
                         <div class="w-full h-full bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px]"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                         <div class="glass-card p-3 bg-teraq-bg/80 border-white/10 flex items-center gap-2">
                            <MapPin class="w-4 h-4 text-teraq-primary" />
                            <span class="text-[10px] font-bold uppercase tracking-widest">214 Market St, San Francisco</span>
                         </div>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Operating Hours -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-teraq-muted">
                            <Clock class="w-4 h-4" />
                            <span class="text-[10px] font-bold uppercase tracking-widest">Operating Hours</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-teraq-muted font-medium">Monday — Thursday</span>
                                <span class="text-white font-bold tracking-wider">11:00 AM — 10:00 PM</span>
                            </div>
                            <div class="flex justify-between text-sm bg-white/5 -mx-4 px-4 py-2 rounded-lg border border-white/5">
                                <span class="text-teraq-accent font-medium">Friday — Saturday</span>
                                <span class="text-white font-bold tracking-wider">11:00 AM — 12:00 AM</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-teraq-muted font-medium">Sunday</span>
                                <span class="text-white font-bold tracking-wider">10:00 AM — 09:00 PM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="space-y-4">
                        <span class="text-teraq-muted text-[10px] font-bold uppercase tracking-widest">Amenities</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div v-for="amenity in amenities" :key="amenity.name" class="flex items-center gap-3 bg-white/5 rounded-xl px-4 py-3 border border-white/5 active:scale-[0.98] transition-all cursor-pointer hover:bg-white/10">
                                <component :is="amenity.icon" class="w-4 h-4 text-teraq-muted" />
                                <span class="text-[11px] font-bold uppercase tracking-wider text-white/80">{{ amenity.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </BaseCard>

            <!-- Live joined ticker -->
            <div class="glass-card p-4 bg-teraq-surface border-white/5 flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img v-for="i in 3" :key="i" :src="`https://i.pravatar.cc/150?u=${i}`" class="w-8 h-8 rounded-full border-2 border-teraq-bg" />
                    <div class="w-8 h-8 rounded-full border-2 border-teraq-bg bg-teraq-border flex items-center justify-center text-[8px] font-bold text-white">
                        +12
                    </div>
                </div>
                <p class="text-[11px] text-teraq-muted leading-tight">
                    <span class="text-white font-bold">Alex and 14 others</span> joined the queue in the last hour.
                </p>
            </div>
        </main>
    </div>
</template>
