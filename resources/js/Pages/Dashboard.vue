<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const stats = [
    {
        title: 'Total Revenue',
        value: '$48,352',
        trend: '+12.5% vs last month',
        trendUp: true,
        icon: 'currency',
        bgColor: 'bg-primary-100',
        iconColor: 'text-primary-600',
    },
    {
        title: 'Pending Invoices',
        value: '12',
        trend: '+3 from last week',
        trendUp: true,
        icon: 'document',
        bgColor: 'bg-warning-100',
        iconColor: 'text-warning-600',
    },
    {
        title: 'Active Clients',
        value: '84',
        trend: '+5 new this month',
        trendUp: true,
        icon: 'users',
        bgColor: 'bg-success-100',
        iconColor: 'text-success-600',
    },
    {
        title: 'Overdue Amount',
        value: '$3,240',
        trend: '2 invoices overdue',
        trendUp: false,
        icon: 'exclamation',
        bgColor: 'bg-danger-100',
        iconColor: 'text-danger-600',
    },
];

const recentInvoices = [
    { name: 'Acme Corporation', number: 'INV-2024-001', amount: '$2,450.00', status: 'paid', date: 'Aug 10, 2024' },
    { name: 'TechStart Inc', number: 'INV-2024-002', amount: '$1,200.00', status: 'pending', date: 'Aug 12, 2024' },
    { name: 'Global Solutions', number: 'INV-2024-003', amount: '$5,800.00', status: 'overdue', date: 'Aug 5, 2024' },
    { name: 'Digital Agency', number: 'INV-2024-004', amount: '$3,100.00', status: 'draft', date: 'Aug 13, 2024' },
];

// const quickActions = [
//     { name: 'Create Invoice', href: route('invoices.create'), icon: 'plus', bg: 'bg-primary-100', hover: 'hover:bg-primary-500', text: 'text-primary-600 hover:text-white', badge: 'badge-primary' },
//     { name: 'Add Client', href: route('clients.create'), icon: 'users-add', bg: 'bg-success-100', hover: 'hover:bg-success-500', text: 'text-success-600 hover:text-white', badge: 'badge-success' },
//     { name: 'Add Product', href: route('products.create'), icon: 'cube', bg: 'bg-accent-100', hover: 'hover:bg-accent-500', text: 'text-accent-600 hover:text-white', badge: 'badge-secondary' },
//     { name: 'View Reports', href: route('reports.index'), icon: 'chart', bg: 'bg-secondary-100', hover: 'hover:bg-secondary-500', text: 'text-secondary-600 hover:text-white', badge: 'badge-secondary' },
// ];

const statusColors = {
    paid: 'badge-success',
    pending: 'badge-warning',
    overdue: 'badge-danger',
    draft: 'badge-secondary',
};

const iconPaths = {
    currency: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    document: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    users: 'M17 20h5v-2a3 3 0 00-3-3h-2v-2a3 3 0 00-3-3h-2a3 3 0 00-3 3v2H9a3 3 0 00-3 3v2a3 3 0 003 3h5m0 0v-1a3 3 0 00-3-3h-2a3 3 0 00-3 3v1m0 0H5a3 3 0 01-3-3v-6a3 3 0 013-3h2a3 3 0 013 3v6a3 3 0 013 3z',
    users_add: 'M19 11h2m-2 0a4.5 4.5 0 01-4.412 5H9.5a4.5 4.5 0 01-4.412-5h12.824zM15.5 3a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM5 20a3 3 0 013-3h11a3 3 0 013 3v2m-2 0h-2m0 0v-1a1 1 0 00-1-1m-4 1v1m0 0h2m-2 0H9m4 0v-1a1 1 0 00-1-1',
    cube: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    chart: 'M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6',
    exclamation: 'M12 8.5v6m0 0v.5m0-.5h.01M12 4.5a7.5 7.5 0 100 15 7.5 7.5 0 000-15z',
    plus: 'M12 4v16m8-8H4',
};

function getIconSvg(name) {
    const path = iconPaths[name];
    if (!path) return '';
    return `<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${path}" /></svg>`;
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle mt-1">Welcome back! Here's what's happening with your business.</p>
                </div>
                <div class="flex items-center gap-3">
                    <PrimaryButton class="btn-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Invoice
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                v-for="(stat, i) in stats"
                :key="stat.title"
                class="stat-card animate-fade-in"
                :style="{ animationDelay: `${i * 100}ms` }"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="stat-label">{{ stat.title }}</p>
                        <p class="stat-value">{{ stat.value }}</p>
                        <p class="stat-trend" :class="stat.trendUp ? 'text-success-600' : 'text-danger-600'">
                            <svg v-if="stat.trendUp" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l-7 7m0 0l7 7m-7-7v18" />
                            </svg>
                            {{ stat.trend }}
                        </p>
                    </div>
                    <div class="h-14 w-14 rounded-xl flex items-center justify-center" :class="stat.bg">
                        <div v-html="getIconSvg(stat.icon)" :class="stat.iconColor" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 card-hover p-6 animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-dark-900">Revenue Overview</h2>
                    <select class="input w-auto px-3 py-1.5 text-sm">
                        <option>Last 30 days</option>
                        <option>Last 7 days</option>
                        <option>Last 90 days</option>
                        <option>Last year</option>
                    </select>
                </div>
                <div class="h-64 relative">
                    <div class="absolute inset-0 flex items-end justify-around h-full px-4">
                        <div
                            v-for="(value, index) in [45, 52, 38, 65, 71, 58, 82, 69, 77, 85, 92, 78]"
                            :key="index"
                            class="flex-1 max-w-[40px] flex flex-col items-center justify-end transition-all duration-500"
                        >
                            <div
                                class="w-full rounded-t-xl bg-gradient-to-t from-primary-500 to-primary-400"
                                :style="{ height: `${value}%` }"
                            />
                            <span class="text-xs text-dark-400 mt-2">{{ ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][index] }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-4 right-4 h-px bg-gradient-to-r from-transparent via-dark-200 to-transparent" />
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="card-hover p-6 animate-fade-in">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-dark-900">Recent Invoices</h2>

                </div>
                <div class="space-y-4">
                    <div
                        v-for="invoice in recentInvoices"
                        :key="invoice.number"
                        class="flex items-center justify-between p-4 rounded-xl bg-dark-50 hover:bg-dark-100 transition-colors"
                    >
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center shadow-soft">
                                <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-dark-900">{{ invoice.name }}</p>
                                <p class="text-sm text-dark-500">{{ invoice.number }} · {{ invoice.date }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-semibold text-dark-900">{{ invoice.amount }}</span>
                            <span :class="statusColors[invoice.status]" class="badge">
                                {{ invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in">
            <Link
                v-for="(action, i) in quickActions"
                :key="action.name"
                :href="action.href"
                class="card-hover p-6 text-center group"
                :style="{ animationDelay: `${i * 100}ms` }"
            >
                <div class="h-14 w-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all" :class="[action.bg, action.hover, action.text]">
                    <div v-html="getIconSvg(action.icon)" />
                </div>
                <h3 class="font-semibold text-dark-900 mb-1">{{ action.name }}</h3>
                <p class="text-sm text-dark-500">
                    {{ action.name === 'Create Invoice' ? 'Generate a new invoice' :
                       action.name === 'Add Client' ? 'Register new client' :
                       action.name === 'Add Product' ? 'Create new product' :
                       'Business analytics' }}
                </p>
            </Link>
        </div> -->
    </AuthenticatedLayout>
</template>
