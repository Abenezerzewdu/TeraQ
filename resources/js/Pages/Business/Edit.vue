<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import BaseCard from '@/Components/BaseCard.vue';
import QueueButton from '@/Components/QueueButton.vue';
import { 
    Store, 
    MapPin, 
    ChevronLeft,
    Save,
    Trash2,
    Upload,
    Image as ImageIcon
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    business: Object,
});

const form = useForm({
    _method: 'patch',
    name: props.business.name,
    location: props.business.location || "",
    hero_image: null,
    logo: null,
});

const heroImagePreview = ref(props.business.hero_image_path ? `/storage/${props.business.hero_image_path}` : null);
const logoPreview = ref(props.business.logo_path ? `/storage/${props.business.logo_path}` : null);

const handleHeroImageChange = (e) => {
    form.hero_image = e.target.files[0];
    if (form.hero_image) {
        heroImagePreview.value = URL.createObjectURL(form.hero_image);
    } else if (props.business.hero_image_path) {
        heroImagePreview.value = `/storage/${props.business.hero_image_path}`;
    } else {
        heroImagePreview.value = null;
    }
};

const handleLogoChange = (e) => {
    form.logo = e.target.files[0];
    if (form.logo) {
        logoPreview.value = URL.createObjectURL(form.logo);
    } else if (props.business.logo_path) {
        logoPreview.value = `/storage/${props.business.logo_path}`;
    } else {
        logoPreview.value = null;
    }
};

const submit = () => {
    form.post(route('businesses.update', props.business.slug), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const deleteBusiness = () => {
    if (confirm('Are you sure you want to delete this business? This action cannot be undone and all associated queues will be removed.')) {
        form.delete(route('businesses.destroy', props.business.slug));
    }
};
</script>

<template>
    <Head title="Edit Business" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-8">
            <header class="mb-12 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                         <Link :href="route('dashboard')" class="text-teraq-muted hover:text-white transition-colors flex items-center gap-1 text-xs font-bold uppercase tracking-widest">
                            <ChevronLeft class="w-4 h-4" />
                            Back to Dashboard
                        </Link>
                    </div>
                    <h1 class="text-white text-5xl font-bold tracking-tight">Edit <span class="bg-gradient-to-r from-teraq-accent to-teraq-primary bg-clip-text text-transparent">Business</span></h1>
                    <p class="text-teraq-muted mt-4 text-lg max-w-xl">
                        Update your business details or manage its presence on TeraQ.
                    </p>
                </div>
                
                <button @click="deleteBusiness" class="px-6 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 font-bold hover:bg-red-500 hover:text-white transition-all flex items-center gap-2">
                    <Trash2 class="w-4 h-4" />
                    DELETE BUSINESS
                </button>
            </header>

            <div class="grid grid-cols-1 gap-12">
                <main>
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
                                            required
                                        />
                                        <Store class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted/50" />
                                    </div>
                                    <div v-if="form.errors.name" class="text-red-400 text-xs mt-2">{{ form.errors.name }}</div>
                                </div>

                                <!-- Storefront Location -->
                                <div>
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-3">Storefront Location</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.location" 
                                            placeholder="e.g. 123 Main St, New York, NY" 
                                            class="w-full bg-teraq-bg border-teraq-border border-2 rounded-xl px-4 py-4 text-white focus:ring-teraq-primary/30 focus:border-teraq-primary focus:bg-teraq-surface transition-all placeholder:text-teraq-muted/30"
                                        />
                                        <MapPin class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-teraq-muted/50" />
                                    </div>
                                    <div v-if="form.errors.location" class="text-red-400 text-xs mt-2">{{ form.errors.location }}</div>
                                </div>

                                <!-- Brand Visuals -->
                                <div class="pt-4">
                                    <label class="block text-teraq-muted text-[10px] font-bold uppercase tracking-[0.2em] mb-4">Brand Visuals</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                    </div>
                                    <div v-if="form.errors.hero_image" class="text-red-400 text-xs mt-2">{{ form.errors.hero_image }}</div>
                                    <div v-if="form.errors.logo" class="text-red-400 text-xs mt-2">{{ form.errors.logo }}</div>
                                </div>
                            </div>

                            <template #footer>
                                <div class="flex items-center justify-end">
                                    <QueueButton type="submit" showArrow :loading="form.processing">
                                        SAVE CHANGES
                                    </QueueButton>
                                </div>
                            </template>
                        </BaseCard>
                    </form>
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
