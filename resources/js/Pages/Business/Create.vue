<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import BaseCard from '@/Components/BaseCard.vue';
import QueueButton from '@/Components/QueueButton.vue';
import { 
    Store, 
    MapPin, 
    Image as ImageIcon, 
    Clock, 
    ChevronDown,
    Map as MapIcon,
    Plus,
    Upload,
    Circle,
    Zap
} from 'lucide-vue-next';

const form = useForm({
    name: "",
    location: "",
    hero_image: null,
    logo: null,
});

const heroImagePreview = ref(null);
const logoPreview = ref(null);

const handleHeroImageChange = (e) => {
    form.hero_image = e.target.files[0];
    if (form.hero_image) {
        heroImagePreview.value = URL.createObjectURL(form.hero_image);
    } else {
        heroImagePreview.value = null;
    }
};

const handleLogoChange = (e) => {
    form.logo = e.target.files[0];
    if (form.logo) {
        logoPreview.value = URL.createObjectURL(form.logo);
    } else {
        logoPreview.value = null;
    }
};

const steps = [
    { id: 1, title: 'Identity', subtitle: 'BUSINESS BASICS', active: true },
    { id: 2, title: 'Location', subtitle: 'MAP PRESENCE', active: false },
    { id: 3, title: 'Visuals', subtitle: 'MEDIA ASSETS', active: false },
];

const submit = () => {
    form.post(route('businesses.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Business" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8">
            <header class="mb-12">
                <h1 class="text-white text-5xl font-bold tracking-tight">Establish Your <span class="bg-gradient-to-r from-teraq-accent to-teraq-primary bg-clip-text text-transparent">Presence</span></h1>
                <p class="text-teraq-muted mt-4 text-lg max-w-xl">
                    Define your operational identity within the TeraQ ecosystem. Get started by setting up your profile.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Steps indicator -->
                <aside class="lg:col-span-4 space-y-8">
                    <div v-for="step in steps" :key="step.id" class="flex items-center gap-4 group">
                        <div 
                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-300 border-2"
                            :class="[step.active ? 'bg-teraq-primary/20 border-teraq-primary text-white scale-110 shadow-lg shadow-teraq-primary/20' : 'bg-teraq-surface border-teraq-border text-teraq-muted']"
                        >
                            {{ step.id }}
                        </div>
                        <div>
                            <h4 class="font-bold text-sm tracking-wide" :class="[step.active ? 'text-white' : 'text-teraq-muted']">{{ step.title }}</h4>
                            <p class="text-[10px] font-bold text-teraq-muted/50 uppercase tracking-[0.2em]">{{ step.subtitle }}</p>
                        </div>
                    </div>
                </aside>

                <!-- Form Content -->
                <main class="lg:col-span-8">
                    <form @submit.prevent="submit" class="space-y-8">
                        <BaseCard class="p-8">
                            <div class="space-y-6">
                                <!-- Business Name -->
                                <div>
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-3">Business Name</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.name" 
                                            placeholder="e.g. Eco Bean Cafe" 
                                            class="w-full bg-teraq-bg border-teraq-border border-2 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary focus:bg-teraq-surface transition-all placeholder:text-teraq-muted/30"
                                        />
                                        <Store class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted/50" />
                                    </div>
                                    <div v-if="form.errors.name" class="text-red-400 text-xs mt-2">{{ form.errors.name }}</div>
                                </div>

                                <!-- Industry Category -->
                                <div>
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-3">Industry Category</label>
                                    <div class="relative">
                                        <select 
                                            v-model="form.category"
                                            class="w-full bg-teraq-bg border-teraq-border border-2 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary appearance-none cursor-pointer"
                                        >
                                            <option>Retail & Boutiques</option>
                                            <option>Healthcare</option>
                                            <option>Dining</option>
                                            <option>Banking</option>
                                        </select>
                                        <ChevronDown class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted/50 pointer-events-none" />
                                    </div>
                                </div>

                                <!-- Operating Hours -->
                                <div>
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-3">Operating Hours</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.operating_hours"
                                            placeholder="09:00 - 18:00"
                                            class="w-full bg-teraq-bg border-teraq-border border-2 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary"
                                        />
                                        <Clock class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted/50" />
                                    </div>
                                </div>

                                <!-- Storefront Location -->
                                <div class="pt-4">
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-4">Storefront Location</label>
                                    <div class="relative h-48 rounded-2xl bg-teraq-bg border-teraq-border border-2 overflow-hidden group cursor-pointer border-dashed">
                                        <div class="absolute inset-0 bg-[#1e40af]/10 flex flex-col items-center justify-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-teraq-primary/20 flex items-center justify-center text-teraq-primary group-hover:scale-110 transition-transform">
                                                <MapPin class="w-6 h-6" />
                                            </div>
                                            <span class="text-teraq-primary font-bold text-sm bg-teraq-primary/10 px-4 py-2 rounded-xl border border-teraq-primary/20">Pin Address</span>
                                        </div>
                                        <!-- Map aesthetic dots -->
                                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#2563eb_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
                                    </div>
                                </div>

                                <!-- Brand Visuals -->
                                <div class="pt-4">
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-4">Brand Visuals</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="h-40 bg-teraq-bg border-teraq-border border-2 rounded-2xl border-dashed flex flex-col items-center justify-center gap-2 group cursor-pointer hover:border-teraq-primary/30 transition-all relative overflow-hidden">
                                                <input type="file" @change="handleHeroImageChange" class="hidden" accept="image/*" />
                                                <div v-if="heroImagePreview" class="absolute inset-0 z-0">
                                                    <img :src="heroImagePreview" class="w-full h-full object-cover opacity-40" />
                                                </div>
                                                <div class="relative z-10 p-3 bg-white/5 rounded-xl group-hover:bg-teraq-primary/10 transition-colors">
                                                    <Upload class="w-6 h-6 text-teraq-muted group-hover:text-teraq-primary" />
                                                </div>
                                                <span class="relative z-10 text-teraq-muted text-[10px] font-bold uppercase tracking-widest">{{ heroImagePreview ? 'Change Hero Image' : 'Upload Hero Image' }}</span>
                                            </label>
                                            <div v-if="form.errors.hero_image" class="text-red-400 text-xs mt-2 text-center">{{ form.errors.hero_image }}</div>
                                        </div>
                                    <div>
                                        <label class="h-40 bg-teraq-bg border-teraq-border border-2 rounded-2xl border-dashed flex flex-col items-center justify-center gap-2 group cursor-pointer hover:border-teraq-primary/30 transition-all relative overflow-hidden">
                                            <input type="file" @change="handleLogoChange" class="hidden" accept="image/*" />
                                            <div v-if="logoPreview" class="absolute inset-0 z-0 flex items-center justify-center p-4">
                                                <img :src="logoPreview" class="max-w-full max-h-full object-contain opacity-40" />
                                            </div>
                                            <div class="relative z-10 p-3 bg-white/5 rounded-xl group-hover:bg-teraq-primary/10 transition-colors">
                                                <ImageIcon class="w-6 h-6 text-teraq-muted group-hover:text-teraq-primary" />
                                            </div>
                                            <span class="relative z-10 text-teraq-muted text-[10px] font-bold uppercase tracking-widest">{{ logoPreview ? 'Change Logo' : 'Upload Logo' }}</span>
                                        </label>
                                        <div v-if="form.errors.logo" class="text-red-400 text-xs mt-2 text-center">{{ form.errors.logo }}</div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <template #footer>
                                <div class="flex items-center justify-between">
                                    <button type="button" class="text-teraq-muted font-bold text-xs uppercase tracking-widest hover:text-white transition-colors">Save Draft</button>
                                    <QueueButton type="submit" showArrow :loading="form.processing">
                                        CREATE BUSINESS
                                    </QueueButton>
                                </div>
                            </template>
                        </BaseCard>
                    </form>
                </main>
            </div>
        </div>

        <!-- Badges footer -->
        <div class="flex flex-wrap items-center gap-4 mt-12 pb-12">
            <div class="px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full flex items-center gap-2">
                <Circle class="w-2 h-2 fill-blue-500 text-blue-500" />
                <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest leading-none pt-0.5">Global Discovery</span>
            </div>
            <div class="px-4 py-2 bg-orange-500/10 border border-orange-500/20 rounded-full flex items-center gap-2">
                <Circle class="w-2 h-2 fill-orange-500 text-orange-500" />
                <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest leading-none pt-0.5">Priority Listing</span>
            </div>
            <div class="px-4 py-2 bg-teraq-surface border border-teraq-border rounded-full flex items-center gap-2">
                <Zap class="w-3 h-3 text-teraq-muted" />
                <span class="text-[10px] font-bold text-teraq-muted uppercase tracking-widest leading-none pt-0.5">Instant Activation</span>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
