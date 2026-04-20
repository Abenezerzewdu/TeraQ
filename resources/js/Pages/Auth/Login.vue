<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-teraq-muted">Please enter your details to sign in.</p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-400 bg-emerald-400/10 p-3 rounded-lg border border-emerald-400/20">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email Address" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="name@company.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />

                <PasswordInput
                    id="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center group cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-teraq-muted group-hover:text-white transition-colors">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-teraq-primary hover:text-teraq-secondary transition-colors font-medium underline-offset-4 hover:underline"
                >
                    Forgot password?
                </Link>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full py-4 text-base"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Sign In
                </PrimaryButton>
            </div>
            
            <div class="text-center mt-6">
                <p class="text-teraq-muted text-sm">
                    Don't have an account? 
                    <Link :href="route('register')" class="text-teraq-primary hover:text-teraq-secondary font-semibold transition-colors underline-offset-4 hover:underline">
                        Create one for free
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>

