<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Users, Building2, ListOrdered, Activity, ArrowUpRight, ShieldCheck } from 'lucide-vue-next';

defineProps({
    stats: Object,
    recent_businesses: Array,
    recent_users: Array,
});
</script>

<template>
    <Head title="Admin Dashboard" />
    <AuthenticatedLayout>
        <!-- Header -->
        <div class="flex items-center gap-3 mb-10">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                <ShieldCheck class="w-5 h-5 text-amber-400" />
            </div>
            <div>
                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold">Administration</p>
                <h1 class="text-white text-2xl font-black tracking-tight">Control Panel</h1>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <Link :href="route('admin.users')" class="glass-card p-5 hover:border-white/10 transition-colors group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold">Total Users</p>
                    <Users class="w-4 h-4 text-teraq-muted group-hover:text-teraq-primary transition-colors" />
                </div>
                <p class="text-4xl font-black text-white">{{ stats.users }}</p>
            </Link>
            <Link :href="route('admin.businesses')" class="glass-card p-5 hover:border-white/10 transition-colors group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold">Businesses</p>
                    <Building2 class="w-4 h-4 text-teraq-muted group-hover:text-teraq-primary transition-colors" />
                </div>
                <p class="text-4xl font-black text-white">{{ stats.businesses }}</p>
            </Link>
            <Link :href="route('admin.queues')" class="glass-card p-5 hover:border-white/10 transition-colors group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold">Queues</p>
                    <ListOrdered class="w-4 h-4 text-teraq-muted group-hover:text-teraq-primary transition-colors" />
                </div>
                <p class="text-4xl font-black text-white">{{ stats.queues }}</p>
            </Link>
            <div class="glass-card p-5 bg-teraq-primary/5 border-teraq-primary/20">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-teraq-primary text-[10px] uppercase tracking-[0.2em] font-bold">Active Now</p>
                    <Activity class="w-4 h-4 text-teraq-primary" />
                </div>
                <p class="text-4xl font-black text-white">{{ stats.entries }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Businesses -->
            <div class="glass-card overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <p class="text-white font-bold text-sm">Recent Businesses</p>
                    <Link :href="route('admin.businesses')" class="text-teraq-primary text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 hover:text-white transition-colors">
                        View All <ArrowUpRight class="w-3 h-3" />
                    </Link>
                </div>
                <div class="divide-y divide-white/5">
                    <div v-for="b in recent_businesses" :key="b.id" class="px-6 py-3 flex items-center justify-between hover:bg-white/[0.02] transition-colors">
                        <div>
                            <p class="text-white text-sm font-semibold">{{ b.name }}</p>
                            <p class="text-teraq-muted text-xs">{{ b.queues_count }} queues</p>
                        </div>
                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-emerald-400 text-[9px] font-bold uppercase tracking-wider">Active</span>
                        </div>
                    </div>
                    <div v-if="!recent_businesses.length" class="px-6 py-8 text-center text-teraq-muted text-sm">No businesses yet.</div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="glass-card overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <p class="text-white font-bold text-sm">Recent Users</p>
                    <Link :href="route('admin.users')" class="text-teraq-primary text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 hover:text-white transition-colors">
                        View All <ArrowUpRight class="w-3 h-3" />
                    </Link>
                </div>
                <div class="divide-y divide-white/5">
                    <div v-for="u in recent_users" :key="u.id" class="px-6 py-3 flex items-center gap-3 hover:bg-white/[0.02] transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-teraq-primary/20 flex items-center justify-center text-teraq-primary text-xs font-black shrink-0">
                            {{ u.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-semibold truncate">{{ u.name }}</p>
                            <p class="text-teraq-muted text-xs truncate">{{ u.email }}</p>
                        </div>
                    </div>
                    <div v-if="!recent_users.length" class="px-6 py-8 text-center text-teraq-muted text-sm">No users yet.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
