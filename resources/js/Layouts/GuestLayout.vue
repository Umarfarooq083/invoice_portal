<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex" style="background: #f0f2f5;">

        <!-- Left Panel - Branding -->
        <div class="hidden xl:flex xl:w-[420px] flex-col justify-between p-10 relative overflow-hidden flex-shrink-0"
            style="background: linear-gradient(155deg, #6d5dfc 0%, #4f3fe0 60%, #3730a3 100%);">

            <!-- Subtle pattern overlay -->
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px), radial-gradient(circle at 75% 75%, white 1px, transparent 1px); background-size: 40px 40px;" />

            <!-- Logo -->
            <Link href="/" class="relative z-10 flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <ApplicationLogo class="h-6 w-6 fill-white" />
                </div>
                <span class="text-lg font-bold text-white">Invoice Portal</span>
            </Link>

            <!-- Hero Content -->
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white leading-tight mb-4">
                    Manage Invoices<br />
                    <span class="text-white/70">Like a Pro</span>
                </h1>
                <p class="text-white/60 text-sm leading-relaxed mb-8">
                    Create, track, and manage professional invoices with ease. Built for modern businesses.
                </p>

                <div class="space-y-3">
                    <div v-for="feat in [
                        { icon: '✓', text: 'Smart form management & tracking' },
                        { icon: '✓', text: 'Real-time status updates' },
                        { icon: '✓', text: 'Export & print invoices' },
                    ]" :key="feat.text" class="flex items-center gap-2.5 text-sm">
                        <span class="h-5 w-5 rounded-full bg-white/20 flex items-center justify-center text-white text-xs font-bold">{{ feat.icon }}</span>
                        <span class="text-white/75">{{ feat.text }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10">
                <p class="text-white/40 text-xs">© {{ new Date().getFullYear() }} Invoice Portal. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-[400px]">

                <!-- Mobile logo -->
                <div class="xl:hidden flex items-center justify-center gap-3 mb-8">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center"
                        style="background: linear-gradient(135deg, #6d5dfc, #48cfad);">
                        <ApplicationLogo class="h-6 w-6 fill-white" />
                    </div>
                    <span class="text-xl font-bold text-slate-800">Invoice Portal</span>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl p-8"
                    style="border: 1px solid #eef0f4; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <slot />
                </div>

                <p class="text-center text-xs text-slate-400 mt-5">
                    Secure login powered by Invoice Portal
                </p>
            </div>
        </div>

    </div>
</template>