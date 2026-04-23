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
    Phone,
    Trash2,
    Play,
    UserMinus,
    CheckCircle,
} from "lucide-vue-next";
import BaseCard from "@/Components/BaseCard.vue";
import QueueButton from "@/Components/QueueButton.vue";
import Toast from "@/Components/Toast.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    queue: Object,
    entries: Array,
    isOwner: Boolean,
});

const page = usePage();
const showToast = ref(false);
const toastMsg = ref("");
const notificationPermission = ref(
    typeof Notification !== "undefined" ? Notification.permission : "denied",
);

const requestNotificationPermission = async () => {
    if (typeof Notification === "undefined") return;
    const permission = await Notification.requestPermission();
    notificationPermission.value = permission;

    if (permission === "granted") {
        new Notification("Notifications Enabled!", {
            body: "We'll alert you when it's your turn.",
            icon: "/favicon.ico",
        });
    }
};

const sendBrowserNotification = (title, body) => {
    if (notificationPermission.value === "granted") {
        new Notification(title, {
            body,
            icon: "/favicon.ico",
        });
    }
};

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
            })
            .listen(".user.left", (e) => {
                const leftEntry = entriesList.value.find(
                    (entry) => entry.id === e.entry.id,
                );
                if (leftEntry) {
                    // Update positions for those behind
                    entriesList.value.forEach((entry) => {
                        if (
                            entry.status === "waiting" &&
                            entry.position > leftEntry.position
                        ) {
                            entry.position--;
                        }
                    });
                    // Remove the entry
                    entriesList.value = entriesList.value.filter(
                        (entry) => entry.id !== e.entry.id,
                    );
                }
            })
            .listen(".user.started", (e) => {
                // Someone started serving
                const entry = entriesList.value.find(
                    (ent) => ent.id === e.entry.id,
                );
                
                // If it's the CURRENT user being served
                if (e.entry.device_id === deviceId) {
                     sendBrowserNotification(
                        "It's Your Turn!",
                        `Please head to the counter for ${props.queue.name}.`
                    );
                    toastMsg.value = "It's your turn! Head to the counter.";
                    showToast.value = true;
                }

                if (entry) {
                    entry.status = "serving";
                    entry.position = 0;

                    // Everyone else who was waiting moves up
                    entriesList.value.forEach((ent) => {
                        if (ent.status === "waiting" && ent.id !== entry.id) {
                            ent.position--;
                            
                            // If the current user just moved to position 1
                            if (ent.device_id === deviceId && ent.position === 1) {
                                sendBrowserNotification(
                                    "You're Next!",
                                    `You are now at position 1 for ${props.queue.name}. Get ready!`
                                );
                            }
                        }
                    });
                } else {
                    // Entry not in list (unlikely), push it as serving
                    entriesList.value.push(e.entry);
                }
            })
            .listen(".user.removed", (e) => {
                const removedEntry = entriesList.value.find(
                    (entry) => entry.id === e.entry.id,
                );
                if (removedEntry) {
                    // Update positions for those behind
                    entriesList.value.forEach((entry) => {
                        if (
                            entry.status === "waiting" &&
                            entry.position > removedEntry.position
                        ) {
                            entry.position--;
                        }
                    });
                    // Remove the entry
                    entriesList.value = entriesList.value.filter(
                        (entry) => entry.id !== e.entry.id,
                    );

                    if (e.entry.device_id === deviceId) {
                        toastMsg.value = "The business has removed you from the queue.";
                        showToast.value = true;
                    }
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
                toastMsg.value =
                    "You're in! We recommend adding a phone number next time so we can notify you when it's your turn.";
            }
            showToast.value = true;
            form.reset("name", "phone");
        },
    });
};

const updatePhone = () => {
    form.post(`/queues/${props.queue.slug}/update-phone`, {
        preserveScroll: true,
        onSuccess: () => {
            toastMsg.value = "Awesome! We'll notify you when it's your turn.";
            showToast.value = true;
            form.reset("phone");
        },
    });
};

const leave = () => {
    if (confirm("Are you sure you want to leave the queue?")) {
        form.post(`/queues/${props.queue.slug}/leave`, {
            preserveScroll: true,
            onSuccess: () => {
                toastMsg.value = "You have left the queue.";
                showToast.value = true;
            },
        });
    }
};

const startNext = () => {
    form.post(`/queues/${props.queue.slug}/start-next`, {
        preserveScroll: true,
        onSuccess: () => {
            // Echo will handle the update
        },
    });
};

const removeEntry = (entryId) => {
    if (confirm("Remove this person from the queue?")) {
        form.post(`/queues/${props.queue.slug}/entries/${entryId}/remove`, {
            preserveScroll: true,
            onSuccess: () => {
                toastMsg.value = "User removed.";
                showToast.value = true;
            },
        });
    }
};

const waitingCount = computed(
    () => entriesList.value.filter((e) => e.status === "waiting").length,
);
const currentServing = computed(() =>
    entriesList.value.find((e) => e.status === "serving"),
);

const userEntry = computed(() =>
    entriesList.value.find(
        (e) =>
            e.device_id === deviceId &&
            (e.status === "waiting" || e.status === "serving"),
    ),
);

const myPosition = computed(() => {
    if (!userEntry.value) return null;
    const waitingEntries = entriesList.value.filter(
        (e) => e.status === "waiting",
    );
    return waitingEntries.findIndex((e) => e.id === userEntry.value.id) + 1;
});

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
    <Toast v-if="showToast" :message="toastMsg" @close="showToast = false" />

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

            <!-- Business Management Panel -->
            <div v-if="isOwner" class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-red-500 rounded-[2.5rem] blur opacity-20 animate-pulse"></div>
                <div class="relative glass-card bg-orange-500/10 border-orange-500/20 p-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500 flex items-center justify-center text-white shadow-lg">
                                <Zap class="w-6 h-6 fill-white" />
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-white">Management Console</h4>
                                <p class="text-orange-400/80 text-[10px] font-bold uppercase tracking-widest mt-1">Control the flow</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="text-right mr-4 hidden md:block">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Total Waiting</p>
                                <p class="text-2xl font-black text-white">{{ waitingCount }}</p>
                            </div>
                            <button 
                                @click="startNext"
                                class="flex items-center gap-3 px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-xl shadow-orange-500/20 hover:-translate-y-1 active:scale-95 disabled:opacity-50"
                                :disabled="form.processing || waitingCount === 0"
                            >
                                <Play class="w-5 h-5 fill-white" />
                                Call Next
                            </button>
                        </div>
                    </div>
                </div>
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
                                        entry.device_id === deviceId
                                            ? 'ring-4 ring-teraq-primary ring-offset-4 ring-offset-teraq-bg scale-110 z-10'
                                            : '',
                                        'w-14 h-14 rounded-full border-2 flex items-center justify-center font-black text-lg shadow-lg backdrop-blur-sm relative group cursor-pointer hover:scale-110 transition-transform active:scale-95',
                                    ]"
                                >
                                    <span
                                        v-if="entry.status === 'serving'"
                                        class="animate-pulse"
                                        >★</span
                                    >
                                    <span
                                        v-else-if="entry.device_id === deviceId"
                                        >ME</span
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

                                    <!-- Quick Remove (Owner Only) -->
                                    <button 
                                        v-if="isOwner"
                                        @click.stop="removeEntry(entry.id)"
                                        class="absolute -top-2 -left-2 w-6 h-6 bg-red-500 rounded-full border-2 border-teraq-bg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110 active:scale-90 shadow-lg"
                                        title="Remove from queue"
                                    >
                                        <Trash2 class="w-3 h-3 text-white" />
                                    </button>
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

            <!-- User Status Card (When in Queue) -->
            <div v-if="userEntry" class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2.5rem] blur opacity-20 animate-pulse"
                ></div>
                <div
                    class="relative glass-card bg-emerald-500/10 border-emerald-500/20 p-8 flex flex-col md:flex-row items-center justify-between gap-6"
                >
                    <div
                        class="flex items-center gap-6 text-center md:text-left"
                    >
                        <div
                            class="w-20 h-20 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20"
                        >
                            <span class="text-3xl font-black">{{
                                myPosition
                            }}</span>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-white italic">
                                You're In Line!
                            </h4>
                            <p
                                class="text-emerald-400/80 text-sm font-medium uppercase tracking-widest mt-1"
                            >
                                {{ userEntry.user_name || "Guest" }} •
                                {{
                                    myPosition === 1
                                        ? "You are next!"
                                        : `Wait for ${myPosition - 1} more`
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Notification Permission Button -->
                    <div v-if="notificationPermission !== 'granted'" class="flex items-center gap-2">
                        <button 
                            @click="requestNotificationPermission"
                            class="group relative flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition-all"
                        >
                            <Bell class="w-4 h-4 text-emerald-400 group-hover:animate-bounce" />
                            <span class="text-[10px] font-bold uppercase tracking-widest">Alert Me</span>
                            <div class="absolute -top-1 -right-1 w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                        </button>
                    </div>

                    <!-- Phone Update Section (Only if missing) -->
                    <div v-if="!userEntry.phone" class="w-full md:w-auto flex flex-col gap-2">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-emerald-400/60 uppercase tracking-widest">Get Notified</span>
                            <div class="flex gap-2">
                                <div class="relative group flex-1">
                                    <input 
                                        v-model="form.phone"
                                        type="tel"
                                        placeholder="Enter Phone"
                                        class="w-full bg-emerald-500/10 border-emerald-500/20 rounded-xl px-4 py-2 text-sm text-white placeholder:text-emerald-500/30 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all"
                                    />
                                    <Phone class="absolute right-3 top-1/2 -translate-y-1/2 w-3 h-3 text-emerald-500 opacity-40" />
                                </div>
                                <button 
                                    @click="updatePhone"
                                    class="px-4 py-2 bg-emerald-500 text-teraq-bg rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all disabled:opacity-50"
                                    :disabled="form.processing || !form.phone"
                                >
                                    Notify Me
                                </button>
                            </div>
                            <InputError :message="form.errors.phone" />
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-end gap-1">
                        <span
                            class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]"
                            >Est. Service</span
                        >
                        <span class="text-2xl font-bold text-white"
                            >{{
                                (myPosition - 1) * queue.avg_service_time
                            }}
                            MIN</span
                        >
                    </div>
                </div>

                <button
                    @click="leave"
                    class="w-full mt-4 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all cursor-pointer relative z-20"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Leaving..." : "Leave Line" }}
                </button>
            </div>

            <!-- Join Action -->
            <div
                v-if="!userEntry"
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
                                :class="{
                                    'border-red-500/50': form.errors.name,
                                }"
                            />
                            <User
                                class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted opacity-30 group-focus-within:opacity-100 transition-opacity"
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="relative group">
                            <input
                                v-model="form.phone"
                                type="tel"
                                placeholder="Phone (Optional)"
                                class="w-full bg-white/5 border-white/5 rounded-2xl px-6 py-5 text-base text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/20"
                                :class="{
                                    'border-red-500/50': form.errors.phone,
                                }"
                            />
                            <Phone
                                class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted opacity-30 group-focus-within:opacity-100 transition-opacity"
                            />
                            <InputError :message="form.errors.phone" />
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
