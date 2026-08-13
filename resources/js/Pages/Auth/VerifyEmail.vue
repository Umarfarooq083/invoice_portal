<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-dark-900 mb-2">Verify Your Email</h1>
            <p class="text-sm text-dark-500">
                Thanks for signing up! Before getting started, please verify your
                email address by clicking the link we just emailed to you. If you
                didn't receive the email, we'll be happy to send you another.
            </p>
        </div>

        <div
            v-if="verificationLinkSent"
            class="mb-6 text-sm font-medium text-success-600 bg-success-50 p-3 rounded-xl border border-success-200"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-between pt-4">
                <PrimaryButton
                    class="btn-lg btn-primary"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042.89 5.824 2.39 7.938l3-2.647zM16 12a8 8 0 01-8 8v-12a8 8 0 018 8z" />
                    </svg>
                    <span v-if="form.processing">Sending...</span>
                    <span v-else>Resend Verification Email</span>
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
