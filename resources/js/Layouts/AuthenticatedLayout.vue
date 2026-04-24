<script setup>
import { ref, computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    LayoutDashboard,
    PlusSquare,
    Zap,
    BarChart3,
    Settings,
    LogOut,
    Search,
    Bell,
    HelpCircle,
    Menu,
    X,
    MessageSquare,
    Compass,
    Home,
} from "lucide-vue-next";

const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const { props: pageProps } = usePage();

const navigation = computed(() => {
    const items = [
        {
            name: "Home Dashboard",
            href: route("dashboard"),
            icon: LayoutDashboard,
            current: route().current("dashboard"),
        },
    ];

    if (pageProps.auth.has_business) {
        items.push({
            name: "My Businesses",
            href: route("businesses.index"), // Assuming you have this route
            icon: BarChart3,
            current: route().current("businesses.*"),
        });
    } else {
        items.push({
            name: "Register Business",
            href: route("businesses.create"),
            icon: PlusSquare,
            current: route().current("businesses.create"),
        });
    }

    return items;
});

const topNav = [
    { name: "Explore Queues", href: route("home"), icon: Home },
    { name: "My Activity", href: route("dashboard"), icon: Compass }, // Use dashboard for activity
];
</script>

<template>
    <div class="min-h-screen bg-teraq-bg flex overflow-hidden">
        <!-- Desktop Sidebar -->
        <aside
            class="hidden md:flex flex-col w-72 bg-teraq-surface border-r border-white/5 transition-all duration-300"
            :class="{ 'w-20': !isSidebarOpen }"
        >
            <div class="p-6 flex items-center gap-3">
                <Link :href="route('home')" class="shrink-0 group">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                        <ApplicationLogo class="w-7 h-7" />
                    </div>
                </Link>
                <div
                    v-show="isSidebarOpen"
                    class="transition-opacity duration-300"
                >
                    <h1 class="text-white font-bold text-lg leading-tight">
                        TeraQ Business
                    </h1>
                    <p
                        class="text-teraq-muted text-[10px] uppercase tracking-widest font-medium"
                    >
                        Kinetic Precision
                    </p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="sidebar-link"
                    :class="{
                        'sidebar-link-active': item.current,
                        'justify-center px-2': !isSidebarOpen,
                    }"
                    :title="item.name"
                >
                    <component :is="item.icon" class="w-5 h-5 shrink-0" />
                    <span v-show="isSidebarOpen" class="font-medium">{{
                        item.name
                    }}</span>
                </Link>
            </nav>

            <div class="p-4 mt-auto">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="sidebar-link hover:text-red-400 w-full"
                    :class="{ 'justify-center': !isSidebarOpen }"
                >
                    <LogOut class="w-5 h-5 shrink-0" />
                    <span v-show="isSidebarOpen" class="font-medium"
                        >Log Out</span
                    >
                </Link>
            </div>
        </aside>

        <!-- Mobile Header -->
        <header
            class="md:hidden fixed top-0 left-0 right-0 z-40 bg-teraq-surface/80 backdrop-blur-lg border-b border-white/5 p-4 flex items-center justify-between"
        >
            <div class="flex items-center gap-2">
                <Link :href="route('home')" class="group">
                    <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center transition-transform group-hover:scale-110">
                        <ApplicationLogo class="w-5 h-5" />
                    </div>
                </Link>
                <h1 class="text-white font-bold">TeraQ</h1>
            </div>
            <button @click="isMobileMenuOpen = true" class="text-teraq-muted">
                <Menu class="w-6 h-6" />
            </button>
        </header>

        <!-- Mobile Menu Overlay -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="isMobileMenuOpen"
                class="fixed inset-0 z-50 md:hidden flex flex-col bg-teraq-bg"
            >
                <div
                    class="p-6 flex items-center justify-between border-b border-white/5"
                >
                    <div class="flex items-center gap-2">
                        <Link :href="route('home')" class="group">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center transition-transform group-hover:scale-110">
                                <ApplicationLogo class="w-7 h-7" />
                            </div>
                        </Link>
                        <h1 class="text-white font-bold text-lg">TeraQ</h1>
                    </div>
                    <button
                        @click="isMobileMenuOpen = false"
                        class="text-teraq-muted"
                    >
                        <X class="w-6 h-6" />
                    </button>
                </div>
                <nav class="flex-1 px-6 py-8 space-y-4">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        @click="isMobileMenuOpen = false"
                        class="flex items-center gap-4 text-xl text-teraq-muted"
                        :class="{
                            'text-teraq-primary font-bold': item.current,
                        }"
                    >
                        <component :is="item.icon" class="w-6 h-6" />
                        {{ item.name }}
                    </Link>
                </nav>
                <div class="p-6 border-t border-white/5">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="flex items-center gap-4 text-xl text-red-400"
                    >
                        <LogOut class="w-6 h-6" />
                        Log Out
                    </Link>
                </div>
            </div>
        </Transition>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Bar -->
            <header
                class="h-20 bg-teraq-bg border-b border-white/5 hidden md:flex items-center justify-between px-8 shrink-0"
            >
                <div class="flex items-center gap-8 flex-1">
                    <button
                        @click="toggleSidebar"
                        class="text-teraq-muted hover:text-white transition-colors"
                    >
                        <Menu class="w-5 h-5" />
                    </button>

                    <div class="relative w-full max-w-md group">
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted group-focus-within:text-teraq-primary transition-colors"
                        />
                        <input
                            type="text"
                            placeholder="Find services, queues, or places..."
                            class="w-full bg-teraq-surface border-none rounded-xl pl-10 pr-4 py-2.5 text-sm text-white focus:ring-1 focus:ring-teraq-primary/30 placeholder:text-teraq-muted/50 transition-all"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <nav
                        class="flex items-center gap-1 bg-teraq-surface/50 p-1 rounded-xl border border-white/5"
                    >
                        <Link
                            v-for="item in topNav"
                            :key="item.name"
                            :href="item.href"
                            class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all"
                            :class="[
                                route().current('dashboard') &&
                                item.name === 'Home'
                                    ? 'bg-teraq-primary text-white shadow-lg shadow-teraq-primary/20'
                                    : 'text-teraq-muted hover:text-white',
                            ]"
                        >
                            {{ item.name }}
                        </Link>
                    </nav>

                    <div class="flex items-center gap-3">
                        <button
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-teraq-surface border border-white/5 transition-colors hover:bg-white/5 relative"
                        >
                            <Bell class="w-5 h-5 text-teraq-muted" />
                            <span
                                class="absolute top-2.5 right-3 w-2 h-2 bg-red-500 rounded-full border-2 border-teraq-surface"
                            ></span>
                        </button>
                        <button
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-teraq-surface border border-white/5 transition-colors hover:bg-white/5"
                        >
                            <HelpCircle class="w-5 h-5 text-teraq-muted" />
                        </button>
                    </div>

                    <div class="h-8 w-px bg-white/5"></div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden xl:block">
                            <p class="text-white text-sm font-semibold">
                                {{ $page.props.auth.user.name }}
                            </p>
                            <p
                                class="text-teraq-muted text-[10px] uppercase tracking-wider"
                            >
                                Business Admin
                            </p>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5 shadow-lg overflow-hidden"
                        >
                            <img
                                src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff"
                                alt="Profile"
                                class="w-full h-full object-cover rounded-[10px]"
                            />
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div
                class="flex-1 overflow-y-auto p-4 md:p-8 pt-24 md:pt-8 custom-scrollbar"
            >
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
