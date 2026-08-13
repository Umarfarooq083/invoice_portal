<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 p-12 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute inset-0 opacity-10" style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E&quot;);" />
            
            <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-white/10 blur-3xl" />
            <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-secondary-400/10 blur-3xl" />
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-96 w-96 rounded-full bg-accent-400/5 blur-3xl" />

            <div class="relative z-10">
                <Link href="/" class="inline-flex items-center gap-3 mb-8">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                        <ApplicationLogo class="h-8 w-8 fill-white" />
                    </div>
                    <span class="text-2xl font-bold text-white">InvoicePortal</span>
                </Link>
            </div>

            <div class="relative z-10 max-w-md">
                <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-6 animate-fade-in">
                    Professional Invoicing
                    <br />
                    <span class="text-accent-200">Made Simple</span>
                </h1>
                <p class="text-lg text-primary-100 mb-10 animate-slide-up stagger-1">
                    Create, send, and track invoices effortlessly. 
                    Get paid faster with our intuitive platform designed for modern businesses.
                </p>
                <div class="flex flex-wrap gap-4 animate-slide-up stagger-2">
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Secure Payments</p>
                            <p class="text-primary-200 text-sm">Stripe & PayPal integration</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <div class="h-10 w-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Automated Reminders</p>
                            <p class="text-primary-200 text-sm">Never miss a payment</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-8 border-t border-white/20 animate-slide-up stagger-3">
                <p class="text-primary-200 text-sm">
                    Trusted by <span class="text-white font-semibold">10,000+</span> businesses worldwide
                </p>
                <div class="flex gap-6 mt-4 opacity-60">
                    <div class="h-6 w-20 rounded bg-white/20" />
                    <div class="h-6 w-24 rounded bg-white/20" />
                    <div class="h-6 w-16 rounded bg-white/20" />
                    <div class="h-6 w-20 rounded bg-white/20" />
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-dark-50">
            <div class="w-full max-w-md">
                <div class="text-center mb-10 lg:hidden">
                    <Link href="/" class="inline-flex items-center justify-center gap-3 mx-auto">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600">
                            <ApplicationLogo class="h-7 w-7 fill-white" />
                        </div>
                        <span class="text-xl font-bold text-dark-900">InvoicePortal</span>
                    </Link>
                </div>

                <div class="bg-white rounded-2xl shadow-elegant border border-dark-200 p-8 animate-fade-in">
                    <slot />
                </div>

                <p class="text-center text-sm text-dark-500 mt-6 animate-slide-up">
                    © {{ new Date().getFullYear() }} InvoicePortal. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>