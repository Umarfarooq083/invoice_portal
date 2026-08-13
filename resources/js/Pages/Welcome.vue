<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <div class="relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-primary-200/30 blur-3xl" />
            <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-secondary-200/30 blur-3xl" />
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-accent-200/20 blur-3xl" />

            <!-- Header -->
            <header class="relative z-10 px-6 py-8 sm:px-8">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600">
                            <svg viewBox="0 0 316 316" class="h-6 w-6 fill-white">
                                <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-dark-900">InvoicePortal</span>
                    </div>

                    <nav v-if="canLogin" class="flex items-center gap-3">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-dark-700 hover:text-primary-700 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-dark-600 hover:text-primary-700 transition-colors"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all shadow-soft hover:shadow-card-hover"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </header>

            <!-- Hero Section -->
            <div class="relative z-10 mx-auto max-w-7xl px-6 py-20 sm:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-5xl sm:text-6xl font-extrabold text-dark-900 mb-6 leading-tight">
                        <span class="gradient-text">Professional Invoicing</span>
                        <br />
                        Made Simple
                    </h1>
                    <p class="text-lg text-dark-500 mb-10 max-w-2xl mx-auto">
                        Create, send, and track invoices effortlessly. Get paid faster with our 
                        intuitive platform designed for modern businesses.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <PrimaryButton
                            v-if="canRegister"
                            :href="route('register')"
                            class="btn-lg btn-primary"
                        >
                            Get Started
                        </PrimaryButton>
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="px-6 py-3 rounded-xl text-sm font-semibold text-dark-700 bg-dark-100 hover:bg-dark-200 transition-colors"
                        >
                            Sign in
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="relative z-10 mx-auto max-w-7xl px-6 py-12 sm:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <FeatureCard 
                        icon="shield" 
                        title="Secure" 
                        description="Military-grade encryption and secure payment processing."
                        color="primary"
                        delay="0"
                    />
                    <FeatureCard 
                        icon="lightning" 
                        title="Fast" 
                        description="Create and send invoices in seconds with smart templates."
                        color="success"
                        delay="100"
                    />
                    <FeatureCard 
                        icon="support" 
                        title="Support" 
                        description="24/7 dedicated support from our expert team."
                        color="secondary"
                        delay="200"
                    />
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="relative z-10 mx-auto max-w-7xl px-6 py-16 sm:px-8 border-t border-dark-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <StatItem value="10K+" label="Happy Customers" />
                <StatItem value="50K+" label="Invoices Sent" />
                <StatItem value="98%" label="Uptime" />
                <StatItem value="4.9" label="Rating" />
            </div>
        </div>

        <footer class="relative z-10 py-12 text-center text-sm text-dark-500 border-t border-dark-200">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>
    </div>
</template>

<script>
const FeatureCard = {
    props: {
        icon: String,
        title: String,
        description: String,
        color: { type: String, default: 'primary' },
        delay: { type: String, default: '0' },
    },
    template: `
        <div class="card p-6 text-center animate-fade-in" :style="{ animationDelay: delay + 'ms' }">
            <div class="flex justify-center mb-4">
                <div class="h-14 w-14 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg v-if="icon === 'shield'" class="h-7 w-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.616-2.868L9 21.132a2 2 0 01-2.828 0L1.17 10.784a2 2 0 010-2.828l4.838-4.838a2 2 0 012.828 0L12 4a2 2 0 012.828 0l4.838 4.838a2 2 0 010 2.828z" />
                    </svg>
                    <svg v-else-if="icon === 'lightning'" class="h-7 w-7 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <svg v-else-if="icon === 'support'" class="h-7 w-7 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m-4.242 4.242L9 17l-5-5 3.536-3.536m0 0L9 9l1.414-1.414M7.05 10.95L9 12.9l1.414-1.414" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-dark-900 mb-2">{{ title }}</h3>
            <p class="text-sm text-dark-500">{{ description }}</p>
        </div>
    `
};

const StatItem = {
    props: ['value', 'label'],
    template: `
        <div>
            <p class="text-3xl font-bold gradient-text mb-1">{{ value }}</p>
            <p class="text-sm text-dark-500">{{ label }}</p>
        </div>
    `
};
</script>
