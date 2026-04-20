<script setup>
import { Head, Link } from "@inertiajs/vue3";
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
    ChevronRight,
    Zap,
    Users,
    ShieldCheck,
    Timer,
    Globe,
} from "lucide-vue-next";
import BaseCard from "@/Components/BaseCard.vue";
import QueueButton from "@/Components/QueueButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const features = [
    {
        title: "Join Remotely",
        desc: "Join any queue from your phone before you even arrive.",
        icon: Globe,
        color: "text-blue-400",
    },
    {
        title: "Live Tracking",
        desc: "See your real-time position and estimated wait time.",
        icon: Timer,
        color: "text-emerald-400",
    },
    {
        title: "Instant Alerts",
        desc: "Get notified via SMS or App when it is your turn.",
        icon: Bell,
        color: "text-orange-400",
    },
];

const stats = [
    { label: "Active Users", value: "12k+", icon: Users },
    { label: "Wait Time Saved", value: "450h", icon: Clock },
    { label: "Partnered Shops", value: "800+", icon: Building2 },
];
</script>

<template>
    <Head title="TeraQ | Wait Smart, Live More" />

    <div
        class="min-h-screen bg-teraq-bg text-white font-sans antialiased selection:bg-teraq-primary/30 overflow-x-hidden"
    >
        <!-- Navigation -->
        <nav
            class="fixed top-0 left-0 right-0 z-50 bg-teraq-bg/60 backdrop-blur-xl border-b border-white/5"
        >
            <div
                class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between"
            >
                <div class="flex items-center gap-3 group cursor-pointer">
                    <Link :href="route('home')" class="group flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                            <ApplicationLogo class="w-7 h-7" />
                        </div>
                        <h1 class="text-white font-bold text-2xl tracking-tight">
                            TeraQ
                        </h1>
                    </Link>
                </div>

                <div
                    class="hidden md:flex items-center gap-8 text-sm font-medium text-teraq-muted"
                >
                    <a
                        href="#features"
                        class="hover:text-white transition-colors"
                        >Features</a
                    >
                    <a
                        href="#how-it-works"
                        class="hover:text-white transition-colors"
                        >How it Works</a
                    >
                    <a
                        href="#businesses"
                        class="hover:text-white transition-colors"
                        >For Business</a
                    >
                </div>

                <div v-if="canLogin" class="flex items-center gap-4">
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="route('dashboard')"
                            class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm font-bold hover:bg-white/10 transition-all"
                            >Dashboard</Link
                        >
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-xs font-bold uppercase tracking-widest text-teraq-muted hover:text-white transition-colors"
                            >Log in</Link
                        >
                        <Link
                            :href="route('register')"
                            class="px-6 py-2.5 bg-gradient-to-r from-teraq-primary to-teraq-secondary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all"
                            >Join Now</Link
                        >
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-40 pb-20 md:pt-60 md:pb-40 overflow-hidden">
            <!-- Background Visualization -->
            <div class="absolute inset-0 z-0">
                <img
                    src="/images/hero_vis.png"
                    class="w-full h-full object-cover opacity-40 scale-110 blur-[2px]"
                />
                <div
                    class="absolute inset-0 bg-gradient-to-b from-teraq-bg/20 via-teraq-bg/80 to-teraq-bg"
                ></div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-teraq-bg via-transparent to-teraq-bg"
                ></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary text-[10px] uppercase font-bold tracking-widest mb-8 animate-bounce"
                >
                    <Zap class="w-3 h-3" />
                    Revolutionizing the way you wait
                </div>

                <h1
                    class="text-5xl md:text-8xl font-black tracking-tighter text-white mb-8 leading-[0.9]"
                >
                    Wait for what <br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-teraq-primary via-teraq-accent to-blue-400"
                        >matters</span
                    >, skip the rest.
                </h1>

                <p
                    class="max-w-2xl mx-auto text-teraq-muted text-lg md:text-xl font-medium mb-12 leading-relaxed"
                >
                    TeraQ is the ultimate virtual queuing platform that lets you
                    join lines remotely, track your status in real-time, and get
                    notified when it's your turn.
                </p>

                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-4"
                >
                    <Link
                        :href="route('register')"
                        class="w-full sm:w-auto px-10 py-5 bg-teraq-primary text-white rounded-2xl font-bold text-lg shadow-xl shadow-teraq-primary/20 hover:bg-teraq-secondary hover:-translate-y-1 transition-all flex items-center justify-center gap-2"
                    >
                        Get Started Free <ChevronRight class="w-5 h-5" />
                    </Link>
                    <Link :href="route('businesses')">
                        <button
                            class="w-full sm:w-auto px-10 py-5 bg-white/5 border border-white/10 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all"
                        >
                            Explore Places
                        </button>
                    </Link>
                </div>

                <!-- Stats -->
                <div
                    class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto"
                >
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="p-6 glass-card border-white/5 relative group cursor-default"
                    >
                        <div
                            class="absolute -right-2 -top-2 w-12 h-12 bg-teraq-primary/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"
                        ></div>
                        <p
                            class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest mb-2"
                        >
                            {{ stat.label }}
                        </p>
                        <h3
                            class="text-4xl font-black text-white tracking-tighter"
                        >
                            {{ stat.value }}
                        </h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div
                    class="flex flex-col md:flex-row items-end justify-between gap-8 mb-16"
                >
                    <div class="max-w-xl">
                        <h2
                            class="text-4xl md:text-5xl font-black tracking-tight text-white mb-6"
                        >
                            Built for the <br />
                            Modern Routine
                        </h2>
                        <p class="text-teraq-muted text-lg">
                            We've combined powerful tech with a simple interface
                            to make queuing as painless as possible.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <div
                            class="w-12 h-1 h-32 bg-white/5 rounded-full overflow-hidden shrink-0"
                        >
                            <div class="h-1/3 w-full bg-teraq-primary"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="p-10 glass-card bg-white/[0.02] border-white/5 hover:border-teraq-primary/30 transition-all duration-500 group"
                    >
                        <div
                            :class="[
                                feature.color,
                                'w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-8 border border-white/5 group-hover:scale-110 transition-transform duration-500',
                            ]"
                        >
                            <component :is="feature.icon" class="w-8 h-8" />
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">
                            {{ feature.title }}
                        </h3>
                        <p class="text-teraq-muted leading-relaxed font-medium">
                            {{ feature.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it Works (Visual Steps) -->
        <section id="how-it-works" class="py-24 bg-white/[0.01]">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2
                    class="text-4xl md:text-5xl font-black tracking-tight text-white mb-20 text-center"
                >
                    As Easy as 1-2-3
                </h2>

                <div class="relative grid grid-cols-1 md:grid-cols-3 gap-20">
                    <!-- Connector line -->
                    <div
                        class="hidden md:block absolute top-10 left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-transparent via-teraq-border to-transparent"
                    ></div>

                    <div class="relative group">
                        <div
                            class="w-20 h-20 rounded-full bg-teraq-surface border-4 border-teraq-primary flex items-center justify-center text-2xl font-black text-white mx-auto mb-8 shadow-[0_0_30px_rgba(37,99,235,0.3)]"
                        >
                            1
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">
                            Scan or Search
                        </h4>
                        <p class="text-teraq-muted text-sm font-medium">
                            Scan the QR at the shop or find your favorite place
                            in the app.
                        </p>
                    </div>

                    <div class="relative group">
                        <div
                            class="w-20 h-20 rounded-full bg-teraq-surface border-4 border-teraq-accent flex items-center justify-center text-2xl font-black text-white mx-auto mb-8 shadow-[0_0_30px_rgba(129,140,248,0.3)]"
                        >
                            2
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">
                            Join & Chill
                        </h4>
                        <p class="text-teraq-muted text-sm font-medium">
                            Join the virtual queue. Explore nearby shops or wait
                            in your car.
                        </p>
                    </div>

                    <div class="relative group">
                        <div
                            class="w-20 h-20 rounded-full bg-teraq-surface border-4 border-emerald-500 flex items-center justify-center text-2xl font-black text-white mx-auto mb-8 shadow-[0_0_30px_rgba(16,185,129,0.3)]"
                        >
                            3
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">
                            It's Your Turn
                        </h4>
                        <p class="text-teraq-muted text-sm font-medium">
                            Receive a buzz on your phone when they are ready to
                            serve you.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- For Businesses -->
        <section id="businesses" class="py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div
                    class="glass-card bg-gradient-to-br from-teraq-primary/20 via-teraq-primary/5 to-transparent p-12 md:p-24 border-teraq-primary/20 relative overflow-hidden"
                >
                    <div
                        class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center"
                    >
                        <div>
                            <div class="w-16 h-1 bg-teraq-primary mb-8"></div>
                            <h2
                                class="text-4xl md:text-6xl font-black tracking-tighter text-white mb-8"
                            >
                                Run your business, <br />
                                not the crowd.
                            </h2>
                            <p
                                class="text-teraq-muted text-lg mb-12 font-medium"
                            >
                                Manage customer flow, reduce congestion, and
                                increase satisfaction with our powerful
                                dashboard built for clinics, restaurants,
                                salons, and more.
                            </p>
                            <Link
                                :href="route('businesses.create')"
                                class="inline-flex items-center gap-3 px-8 py-4 bg-white text-teraq-bg rounded-xl font-black hover:scale-105 transition-transform active:scale-95"
                            >
                                Register Your Business
                                <PlusCircle class="w-5 h-5" />
                            </Link>
                        </div>
                        <div class="relative">
                            <div
                                class="aspect-video rounded-3xl overflow-hidden glass-card border-white/10 shadow-2xl relative translate-x-4 md:translate-x-12 rotate-2 scale-110"
                            >
                                <img
                                    src="https://images.unsplash.com/photo-1551288049-bbbda5366991?auto=format&fit=crop&q=80&w=800"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    class="absolute inset-0 bg-teraq-primary/20 mix-blend-overlay"
                                ></div>
                                <div
                                    class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black/80 to-transparent"
                                >
                                    <h5 class="text-white font-bold">
                                        Real-time Dashboard
                                    </h5>
                                    <p class="text-white/60 text-xs">
                                        Monitor every queue at a glance.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Aesthetic decor -->
                    <div
                        class="absolute -right-20 -bottom-20 w-80 h-80 bg-teraq-primary/20 rounded-full blur-[100px]"
                    ></div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-20 border-t border-white/5">
            <div
                class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12"
            >
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center">
                            <ApplicationLogo class="w-5 h-5" />
                        </div>
                        <h2 class="text-white font-bold text-xl">TeraQ</h2>
                    </div>
                    <p
                        class="text-teraq-muted text-sm max-w-sm font-medium leading-relaxed"
                    >
                        Redefining the queuing experience for businesses and
                        customers alike. Built for a faster, smarter world.
                    </p>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6">Product</h5>
                    <ul class="space-y-4 text-sm text-teraq-muted font-medium">
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >Features</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >Mobile App</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >Business Dashboard</a
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-6">Company</h5>
                    <ul class="space-y-4 text-sm text-teraq-muted font-medium">
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >About Us</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >Contact</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="hover:text-white transition-colors"
                                >Privacy Policy</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="max-w-7xl mx-auto px-6 mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4"
            >
                <p
                    class="text-teraq-muted text-[10px] font-bold uppercase tracking-widest"
                >
                    © 2026 TeraQ Inc. All rights reserved.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-teraq-muted hover:text-white"
                        ><Globe class="w-5 h-5"
                    /></a>
                    <a
                        href="#"
                        class="text-teraq-muted hover:text-white transition-colors font-bold text-[10px] tracking-widest uppercase"
                        >Twitter</a
                    >
                    <a
                        href="https://www.instagram.com/abenaww/"
                        class="text-teraq-muted hover:text-white transition-colors font-bold text-[10px] tracking-widest uppercase"
                        >Instagram</a
                    >
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-teraq-surface/40 backdrop-blur-xl border border-white/5 rounded-3xl shadow-glass;
}
</style>
