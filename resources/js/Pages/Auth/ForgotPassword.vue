<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-dark-900 mb-2">Forgot Your Password?</h1>
            <p class="text-sm text-dark-500">No problem. Just enter your email address and we'll send you a password reset link.</p>
        </div>

        <div
            v-if="status"
            class="mb-6 text-sm font-medium text-success-600 bg-success-50 p-3 rounded-xl border border-success-200"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email Address" />
                <div class="relative mt-1.5">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12V4m0 0l4 4m-4-4v8m-4 4h8m-4 4v1m0-1l3-3m-3 3l3 3" />
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

            <div class="mt-8">
                <PrimaryButton
                    class="w-full btn-lg btn-primary"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042.89 5.824 2.39 7.938l3-2.647zM16 12a8 8 0 01-8 8v-12a8 8 0 018 8z" />
                    </svg>
                    <span v-if="form.processing">Sending...</span>
                    <span v-else>Send Password Reset Link</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
