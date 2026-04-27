<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Search, Trash2, ShieldCheck, Building2 } from 'lucide-vue-next';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search ?? '');

let searchTimeout;
watch(search, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('admin.users'), { search: val }, { preserveState: true, replace: true });
    }, 300);
});

const deleteUser = (user) => {
    if (!confirm(`Delete user "${user.name}"? This cannot be undone.`)) return;
    router.delete(route('admin.users.destroy', user.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Admin — Users" />
    <AuthenticatedLayout>
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-teraq-muted text-[10px] uppercase tracking-[0.2em] font-bold mb-1">Administration</p>
                <h1 class="text-white text-2xl font-black tracking-tight">Users</h1>
            </div>
            <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-teraq-muted" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search users..."
                    class="bg-teraq-surface border-white/5 rounded-xl pl-9 pr-4 py-2 text-sm text-white focus:ring-teraq-primary/30 focus:border-teraq-primary transition-all w-56"
                />
            </div>
        </div>

        <div class="glass-card overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted">User</th>
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted hidden md:table-cell">Email</th>
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted hidden lg:table-cell">Businesses</th>
                        <th class="text-left px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-teraq-muted hidden lg:table-cell">Joined</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teraq-primary/20 flex items-center justify-center text-teraq-primary text-xs font-black shrink-0">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="text-white font-semibold">{{ user.name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-teraq-muted hidden md:table-cell">{{ user.email }}</td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <div class="flex items-center gap-1.5 text-teraq-muted">
                                <Building2 class="w-3.5 h-3.5" />
                                {{ user.businesses_count }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-teraq-muted text-xs hidden lg:table-cell">
                            {{ new Date(user.created_at).toLocaleDateString() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="deleteUser(user)"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all ml-auto"
                                title="Delete user"
                            >
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!users.data.length">
                        <td colspan="5" class="px-6 py-12 text-center text-teraq-muted">No users found.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="users.last_page > 1" class="px-6 py-4 border-t border-white/5 flex items-center justify-between">
                <p class="text-teraq-muted text-xs">
                    Showing {{ users.from }}–{{ users.to }} of {{ users.total }}
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="link in users.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                        :class="link.active
                            ? 'bg-teraq-primary text-white'
                            : link.url
                                ? 'text-teraq-muted hover:text-white hover:bg-white/5'
                                : 'text-teraq-muted/30 cursor-not-allowed'"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
