<script setup>
import { useForm, Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
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
    ArrowRight,
    User,
    CheckCircle2,
    Info,
    Phone,
} from "lucide-vue-next";
import BaseCard from "@/Components/BaseCard.vue";
import QueueButton from "@/Components/QueueButton.vue";
import Toast from "@/Components/Toast.vue";

const props = defineProps({
    queue: Object,
    entries: Array,
});

const page = usePage();
const showToast = ref(false);
const toastMsg = ref("");

// ✅ Real-time data sync
const entriesList = ref([...props.entries]);

let channel = null;

onMounted(() => {
    if (window.Echo) {
        channel = window.Echo.channel(`queue.${props.queue.id}`)
            .listen(".user.joined", (e) => {
                const exists = entriesList.value.find(
                    (entry) => entry.id === e.entry.id,
                );
                if (!exists) {
                    entriesList.value.push(e.entry);
                }
            })
            .listen(".user.served", (e) => {
                const index = entriesList.value.findIndex(
                    (entry) => entry.id === e.entry.id,
                );
                if (index !== -1) {
                    entriesList.value.splice(index, 1);
                }
            });
    }
});

onUnmounted(() => {
    if (channel) {
        channel.stopListening(".user.joined");
        channel.stopListening(".user.served");
    }
});

// generate or get device id
let deviceId = localStorage.getItem("teraq_device_id");

if (!deviceId) {
    deviceId = crypto.randomUUID();
    localStorage.setItem("teraq_device_id", deviceId);
}
const form = useForm({
    name: "",
    phone: "",
    device_id: deviceId,
});

const join = () => {
    form.post(`/queues/${props.queue.slug}/join`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            if (form.phone && form.name) {
                toastMsg.value = `Perfect! We'll notify you (${form.phone}) when ${form.name}'s turn is ready.`;
            } else if (form.phone) {
                toastMsg.value = `Success! We'll notify you at ${form.phone}. Next time, add your name to help us identify you!`;
            } else {
                toastMsg.value = "You're in! We recommend adding a phone number next time so we can notify you when it's your turn.";
            }
            showToast.value = true;
            form.reset("name", "phone");
        },
    });
};

const waitingCount = computed(
    () => entriesList.value.filter((e) => e.status === "waiting").length,
);
const currentServing = computed(() =>
    entriesList.value.find((e) => e.status === "serving"),
);

// Colors for avatars
const avatarColors = [
    "bg-blue-500/20 text-blue-400 border-blue-500/30",
    "bg-emerald-500/20 text-emerald-400 border-emerald-500/30",
    "bg-orange-500/20 text-orange-400 border-orange-500/30",
    "bg-purple-500/20 text-purple-400 border-purple-500/30",
    "bg-pink-500/20 text-pink-400 border-pink-500/30",
];

const getAvatarColor = (id) => avatarColors[id % avatarColors.length];

const amenities = [
    { name: "Free WiFi", icon: Wifi },
    { name: "Valet Parking", icon: ParkingCircle },
    { name: "Accessible", icon: Accessibility },
    { name: "Outdoor Seating", icon: CloudSun },
];
</script>

<template>
    <Head :title="queue.name" />

    <!-- Toast Notification -->
    <Toast 
        v-if="showToast" 
        :message="toastMsg" 
        @close="showToast = false" 
    />

    <div
        class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-24 overflow-x-hidden"
    >
        <!-- Top Nav -->
        <header
            class="h-20 border-b border-white/5 flex items-center justify-between px-6 sticky top-0 bg-teraq-bg/80 backdrop-blur-xl z-50"
        >
            <div class="flex items-center gap-4">
                <Link
                    :href="route('business.show', queue.business.slug)"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all"
                >
                    <ChevronLeft class="w-5 h-5" />
                </Link>
                <div class="flex flex-col">
                    <span
                        class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest leading-none mb-1"
                        >Live Queue</span
                    >
                    <span
                        class="font-bold text-base tracking-tight leading-none"
                        >{{ queue.name }}</span
                    >
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 relative"
                >
                    <Bell class="w-5 h-5 text-teraq-muted" />
                    <div
                        class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-orange-500 border-2 border-teraq-bg"
                    ></div>
                </button>
                <div
                    class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5 overflow-hidden"
                >
                    <img
                        src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff"
                        class="w-full h-full object-cover rounded-[10px]"
                    />
                </div>
            </div>
        </header>

        <main class="max-w-2xl mx-auto px-6 py-8 space-y-10">
            <!-- Header Section -->
            <div class="text-center space-y-4">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-bold uppercase tracking-widest mx-auto"
                >
                    <div
                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"
                    ></div>
                    System Online
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight">
                    {{ queue.name }}
                </h1>
                <p class="text-teraq-muted text-sm font-medium">
                    Wait for your turn comfortably while we handle the rest.
                </p>
            </div>

            <!-- Wait Time & Progress -->
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-teraq-primary via-teraq-secondary to-teraq-accent rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"
                ></div>
                <div
                    class="relative glass-card bg-gradient-to-br from-teraq-primary/40 to-teraq-secondary/20 p-10 flex flex-col items-center justify-center min-h-[250px] overflow-hidden"
                >
                    <span
                        class="text-white/60 text-[10px] font-bold uppercase tracking-[0.4em] mb-4"
                        >Estimated Wait</span
                    >
                    <div class="flex items-end gap-3 mb-8">
                        <h2
                            class="text-8xl font-black tracking-tighter text-white drop-shadow-2xl"
                        >
                            {{ waitingCount * queue.avg_service_time }}
                        </h2>
                        <span
                            class="text-2xl font-bold text-white/50 pb-4 uppercase tracking-widest"
                            >Min</span
                        >
                    </div>

                    <!-- Simple Progress Indicator -->
                    <div class="w-full max-w-xs space-y-2">
                        <div
                            class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-white/40"
                        >
                            <span>Processing</span>
                            <span>{{ waitingCount }} in line</span>
                        </div>
                        <div
                            class="h-2 w-full bg-white/10 rounded-full overflow-hidden"
                        >
                            <div
                                class="h-full bg-white rounded-full transition-all duration-1000"
                                :style="`width: ${Math.min(100, (1 - waitingCount / 20) * 100)}%`"
                            ></div>
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div
                        class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"
                    ></div>
                    <div
                        class="absolute -left-10 -top-10 w-40 h-40 bg-teraq-primary/10 rounded-full blur-3xl"
                    ></div>
                </div>
            </div>

            <!-- Animated Line Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <Users class="w-5 h-5 text-teraq-primary" />
                        Live Queue
                    </h3>
                    <span
                        class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest"
                        >Real-time Sync</span
                    >
                </div>

                <div
                    class="glass-card p-8 bg-white/[0.02] border-white/5 overflow-hidden"
                >
                    <div
                        class="flex flex-wrap gap-4 items-center justify-center py-4"
                    >
                        <TransitionGroup name="line">
                            <div
                                v-for="(entry, index) in entriesList"
                                :key="entry.id"
                                class="relative transition-all duration-500"
                            >
                                <!-- Avatar Circle -->
                                <div
                                    :class="[
                                        getAvatarColor(entry.id),
                                        'w-14 h-14 rounded-full border-2 flex items-center justify-center font-black text-lg shadow-lg backdrop-blur-sm relative group cursor-pointer hover:scale-110 transition-transform active:scale-95',
                                    ]"
                                >
                                    <span
                                        v-if="entry.status === 'serving'"
                                        class="animate-pulse"
                                        >★</span
                                    >
                                    <span v-else>{{ index + 1 }}</span>

                                    <!-- Status Badge -->
                                    <div
                                        v-if="entry.status === 'serving'"
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-teraq-bg flex items-center justify-center"
                                    >
                                        <CheckCircle2
                                            class="w-3 h-3 text-white"
                                        />
                                    </div>

                                    <!-- Tooltip / Label -->
                                    <div
                                        class="absolute -bottom-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-white text-teraq-bg text-[8px] font-bold uppercase rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10"
                                    >
                                        {{
                                            entry.status === "serving"
                                                ? "Serving Now"
                                                : "Waiting..."
                                        }}
                                    </div>
                                </div>

                                <!-- Connection line (if not last) -->
                                <div
                                    v-if="index < entriesList.length - 1"
                                    class="absolute top-1/2 -right-4 w-4 h-[1px] bg-white/10"
                                ></div>
                            </div>
                        </TransitionGroup>

                        <!-- Serving Placeholder if empty -->
                        <div
                            v-if="entriesList.length === 0"
                            class="flex flex-col items-center gap-4 py-8"
                        >
                            <div
                                class="w-16 h-16 rounded-full border-2 border-dashed border-white/10 flex items-center justify-center text-white/20"
                            >
                                <Users class="w-8 h-8" />
                            </div>
                            <p
                                class="text-teraq-muted text-xs font-medium uppercase tracking-widest"
                            >
                                No one in line
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Join Action -->
            <div
                class="glass-card p-8 bg-gradient-to-br from-teraq-surface to-transparent border-white/5 space-y-6"
            >
                <div
                    class="flex flex-col items-center text-center space-y-2 mb-2"
                >
                    <h4 class="text-xl font-bold">Ready to join?</h4>
                    <p class="text-teraq-muted text-xs font-medium">
                        Fill in your details to stay in the loop.
                    </p>
                </div>

                <form @submit.prevent="join" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative group">
                            <input
                                v-model="form.name"
                                placeholder="Your Name (Optional)"
                                class="w-full bg-white/5 border-white/5 rounded-2xl px-6 py-5 text-base text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/20"
                            />
                            <User
                                class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted opacity-30 group-focus-within:opacity-100 transition-opacity"
                            />
                        </div>

                        <div class="relative group">
                            <input
                                v-model="form.phone"
                                type="tel"
                                placeholder="Phone (Optional)"
                                class="w-full bg-white/5 border-white/5 rounded-2xl px-6 py-5 text-base text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/20"
                            />
                            <Phone
                                class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted opacity-30 group-focus-within:opacity-100 transition-opacity"
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-6 bg-teraq-primary text-white rounded-2xl font-black text-xl uppercase tracking-[0.1em] shadow-2xl shadow-teraq-primary/20 hover:bg-teraq-secondary hover:-translate-y-1 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-3"
                        :disabled="form.processing"
                    >
                        <template v-if="form.processing">Joining...</template>
                        <template v-else>
                            Join The Line <ArrowRight class="w-6 h-6" />
                        </template>
                    </button>

                    <p
                        class="text-[10px] text-teraq-muted uppercase tracking-[0.2em] font-bold text-center"
                    >
                        <Info class="w-3 h-3 inline mr-1 opacity-50" /> No
                        account required
                    </p>
                </form>
            </div>

            <!-- Business Mini Card -->
            <div
                class="glass-card p-6 flex items-center justify-between border-white/5 group cursor-pointer hover:bg-white/[0.04] transition-all"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400"
                    >
                        <Coffee class="w-6 h-6" />
                    </div>
                    <div>
                        <h5 class="text-white font-bold">
                            {{ queue.business?.name || "Visit Business" }}
                        </h5>
                        <div
                            class="flex items-center gap-2 text-teraq-muted text-[10px] font-bold uppercase tracking-widest"
                        >
                            <MapPin class="w-3 h-3" />
                            Downtown District
                        </div>
                    </div>
                </div>
                <Link
                    :href="`/businesses/${queue.business_id}`"
                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center group-hover:bg-teraq-primary transition-colors"
                >
                    <ChevronRight class="w-5 h-5" />
                </Link>
            </div>
        </main>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-teraq-surface/40 backdrop-blur-xl border border-white/5 rounded-[2rem] shadow-glass;
}

/* Line Animations */
.line-enter-active,
.line-leave-active {
    transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.line-enter-from {
    opacity: 0;
    transform: translateX(30px) scale(0.5);
}
.line-leave-to {
    opacity: 0;
    transform: translateX(-30px) scale(0.5);
}

/* Movement of other elements when one enters/leaves */
.line-move {
    transition: transform 0.6s ease;
}
</style>
