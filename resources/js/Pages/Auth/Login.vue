<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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

        <div v-if="status" class="mb-4 text-sm font-medium text-success-600 bg-success-50 p-3 rounded-xl border border-success-200">
            {{ status }}
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-dark-900 mb-2">Welcome Back</h1>
            <p class="text-sm text-dark-500">Enter your email and password to access your account</p>
        </div>

        <form @submit.prevent="submit">
            <div class="space-y-5">
                <div>
                    <InputLabel for="email" value="Email Address" />
                    <div class="relative mt-1.5">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12V4m0 0l-4 4m4-4l4 4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <TextInput
                            id="email"
                            type="email"
                            class="pl-11"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <div class="relative mt-1.5">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15V3m0 0l-3 3m3-3l3 3M6 15v6a3 3 0 003 3h6a3 3 0 003-3v-6" />
                            </svg>
                        </div>
                        <TextInput
                            id="password"
                            type="password"
                            class="pl-11"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <label class="flex items-start gap-2 cursor-pointer">
                    <div class="mt-0.5">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                    </div>
                    <span class="text-sm text-dark-700">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="mt-8">
                <PrimaryButton
                    class="w-full btn-lg btn-primary"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign In</span>
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-dark-500">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="font-semibold text-primary-600 hover:text-primary-700 transition-colors"
                >
                    Create an account
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
