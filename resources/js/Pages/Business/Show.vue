<script setup>
import { useForm, Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { 
    Plus, 
    X,
    Building2, 
    MapPin, 
    Users, 
    Clock, 
    Zap, 
    ChevronRight,
    ChevronLeft,
    ArrowRight,
    Search,
} from "lucide-vue-next";

const props = defineProps({
    business: Object,
});

const page = usePage();
const isOwner = computed(() => page.props.auth.user?.id === props.business.owner_id);

const showCreateForm = ref(false);
const searchQuery = ref('');

const filteredQueues = computed(() => {
    if (!searchQuery.value) return props.business.queues ?? [];
    return (props.business.queues ?? []).filter(q =>
        q.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const form = useForm({
    name: '',
    avg_service_time: 10,
});

const submitQueue = () => {
    form.post(`/businesses/${props.business.slug}/queues`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showCreateForm.value = false;
        },
    });
};
</script>

<template>
    <Head :title="business.name" />

    <div class="min-h-screen bg-teraq-bg text-white font-sans antialiased pb-24 selection:bg-teraq-primary/30">
        <!-- Top Nav -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-teraq-bg/60 backdrop-blur-xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link href="/" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all group">
                        <ChevronLeft class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teraq-primary to-teraq-secondary flex items-center justify-center">
                            <span class="text-white font-bold text-xs">T</span>
                        </div>
                        <span class="font-bold text-lg tracking-tight">TeraQ</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 border border-white/5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-500">Live Status</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl border-2 border-white/10 p-0.5">
                        <img src="https://ui-avatars.com/api/?name=User&background=2563eb&color=fff" class="w-full h-full object-cover rounded-[10px]" />
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-32 space-y-12">
            <!-- Business Hero -->
            <div class="relative rounded-[2.5rem] overflow-hidden group">
                <div class="absolute inset-0 z-0">
                    <img
                        :src="business.hero_image_path
                            ? `/storage/${business.hero_image_path}`
                            : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=2000'"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-teraq-bg via-teraq-bg/40 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-teraq-bg/80 via-transparent to-transparent"></div>
                </div>

                <div class="relative z-10 p-8 md:p-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teraq-primary/20 backdrop-blur-md border border-teraq-primary/30 text-white text-xs font-bold uppercase tracking-widest">
                            <Building2 class="w-4 h-4" />
                            Official Business Profile
                        </div>
                        <div class="flex items-center gap-6">
                            <div v-if="business.logo_path" class="w-24 h-24 rounded-2xl bg-white/10 p-2 backdrop-blur-md border border-white/20 shrink-0">
                                <img :src="`/storage/${business.logo_path}`" class="w-full h-full object-contain rounded-xl" />
                            </div>
                            <div>
                                <h1 class="text-5xl md:text-7xl font-black tracking-tighter text-white mb-4">{{ business.name }}</h1>
                                <div class="flex items-center gap-3 text-teraq-muted">
                                    <MapPin class="w-5 h-5 text-teraq-primary" />
                                    <span class="text-lg font-medium">{{ business.location || 'Location not specified' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="glass-card px-8 py-6 bg-white/5 backdrop-blur-2xl border-white/10 flex flex-col items-center justify-center min-w-[140px]">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted mb-2">Total Queues</span>
                            <span class="text-4xl font-black text-white">{{ business.queues?.length || 0 }}</span>
                        </div>
                        <div class="glass-card px-8 py-6 bg-teraq-primary/10 backdrop-blur-2xl border-teraq-primary/20 flex flex-col items-center justify-center min-w-[140px]">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-primary mb-2">Wait Level</span>
                            <span class="text-2xl font-black text-white">LOW</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queues Section -->
            <div class="space-y-6">
                <!-- Section Header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black tracking-tight flex items-center gap-3">
                        <Zap class="w-6 h-6 text-teraq-primary fill-teraq-primary" />
                        Active Queues
                    </h2>
                    <div class="flex items-center gap-3">
                        <!-- Search -->
                        <div v-if="business.queues?.length" class="relative group">
                            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted group-focus-within:text-teraq-primary transition-colors" />
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Filter queues..."
                                class="bg-white/5 border-white/5 rounded-xl pl-9 pr-4 py-2 text-sm text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all w-44"
                            />
                        </div>

                        <!-- Add Queue button — owner only -->
                        <button
                            v-if="isOwner"
                            @click="showCreateForm = !showCreateForm"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all"
                            :class="showCreateForm
                                ? 'bg-white/10 border border-white/10 text-white'
                                : 'bg-teraq-primary/10 border border-teraq-primary/20 text-teraq-primary hover:bg-teraq-primary hover:text-white'"
                        >
                            <component :is="showCreateForm ? X : Plus" class="w-4 h-4" />
                            {{ showCreateForm ? 'Cancel' : 'New Queue' }}
                        </button>
                    </div>
                </div>

                <!-- Create Queue Form (inline, slides in) -->
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-if="showCreateForm" class="glass-card p-6 bg-gradient-to-br from-teraq-primary/10 to-transparent border-teraq-primary/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-primary mb-5">New Queue</p>
                        <form @submit.prevent="submitQueue" class="flex flex-col sm:flex-row gap-4 items-end">
                            <div class="flex-1 space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted">Queue Name</label>
                                <input
                                    v-model="form.name"
                                    placeholder="e.g. Appointment Line"
                                    class="w-full bg-teraq-bg/60 border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all placeholder:text-teraq-muted/40"
                                    required
                                    autofocus
                                />
                                <p v-if="form.errors.name" class="text-red-400 text-xs">{{ form.errors.name }}</p>
                            </div>
                            <div class="w-full sm:w-44 space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted">Avg. Service Time (min)</label>
                                <input
                                    v-model="form.avg_service_time"
                                    type="number"
                                    min="1"
                                    class="w-full bg-teraq-bg/60 border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all"
                                    required
                                />
                                <p v-if="form.errors.avg_service_time" class="text-red-400 text-xs">{{ form.errors.avg_service_time }}</p>
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="shrink-0 flex items-center gap-2 px-6 py-3 bg-teraq-primary text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-teraq-secondary transition-all shadow-lg shadow-teraq-primary/20 disabled:opacity-50"
                            >
                                <Plus class="w-4 h-4" />
                                {{ form.processing ? 'Creating...' : 'Create' }}
                            </button>
                        </form>
                    </div>
                </Transition>

                <!-- Queue Cards -->
                <div v-if="filteredQueues.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="queue in filteredQueues" :key="queue.id" class="group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-teraq-primary to-teraq-accent rounded-3xl blur opacity-0 group-hover:opacity-20 transition duration-500"></div>
                        <div class="relative glass-card p-6 bg-white/[0.03] border-white/5 hover:border-white/10 transition-all flex flex-col h-full">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-teraq-primary/10 flex items-center justify-center text-teraq-primary">
                                    <Users class="w-6 h-6" />
                                </div>
                                <div class="px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-bold uppercase tracking-wider">
                                    Live
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">{{ queue.name }}</h3>
                            <p class="text-teraq-muted text-sm flex items-center gap-2 mb-8">
                                <Clock class="w-4 h-4" />
                                {{ queue.avg_service_time }} min avg. wait
                            </p>
                            <div class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest mb-1">Queue ID</p>
                                    <p class="text-xs font-mono text-white/60">#{{ queue.id.toString().padStart(4, '0') }}</p>
                                </div>
                                <Link
                                    :href="`/queues/${queue.slug}`"
                                    class="px-5 py-2.5 bg-teraq-primary/10 border border-teraq-primary/20 rounded-xl text-teraq-primary text-xs font-bold uppercase tracking-widest hover:bg-teraq-primary hover:text-white transition-all flex items-center gap-2"
                                >
                                    Open <ArrowRight class="w-3 h-3" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else-if="!business.queues?.length" class="glass-card p-12 text-center border-dashed border-white/10 bg-transparent">
                    <button
                        v-if="isOwner"
                        @click="showCreateForm = true"
                        class="w-16 h-16 rounded-full bg-white/5 hover:bg-teraq-primary/10 hover:border-teraq-primary/30 border-2 border-dashed border-white/10 flex items-center justify-center mx-auto mb-6 transition-all group"
                    >
                        <Plus class="w-8 h-8 text-teraq-muted group-hover:text-teraq-primary transition-colors" />
                    </button>
                    <div v-else class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                        <Users class="w-8 h-8 text-teraq-muted/30" />
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">No Queues Yet</h4>
                    <p class="text-teraq-muted max-w-xs mx-auto text-sm">
                        {{ isOwner ? 'Click the + above to create your first queue.' : 'This business has no active queues.' }}
                    </p>
                </div>

                <!-- No search results -->
                <div v-else class="glass-card p-8 text-center bg-transparent border-dashed border-white/10">
                    <p class="text-teraq-muted text-sm">No queues match "<span class="text-white">{{ searchQuery }}</span>"</p>
                    <button @click="searchQuery = ''" class="mt-3 text-teraq-primary text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">Clear</button>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.glass-card {
    @apply bg-teraq-surface/40 backdrop-blur-xl border border-white/5 rounded-3xl shadow-glass;
}
</style>
