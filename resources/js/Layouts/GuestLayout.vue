<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen flex flex-col items-center justify-center bg-teraq-bg overflow-hidden font-sans selection:bg-teraq-primary/30">
        <!-- Background Decorative Elements -->
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-teraq-primary/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-teraq-secondary/10 rounded-full blur-[120px]"></div>
        
        <!-- Logo -->
        <div class="relative z-10 mb-8 transform transition-all duration-500 hover:scale-110">
            <Link href="/">
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl">
                    <ApplicationLogo class="h-12 w-12 fill-current text-white" />
                </div>
            </Link>
        </div>

        <!-- Glass Card -->
        <div class="relative z-10 w-full sm:max-w-md">
            <div class="glass-card p-8 mx-4 sm:mx-0 transition-all duration-300">
                <slot />
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-teraq-muted text-sm font-medium">
                    &copy; {{ new Date().getFullYear() }} TeraQ &bull; Premium Queueing
                </p>
            </div>
        </div>
    </div>
</template>

