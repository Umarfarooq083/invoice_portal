<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const stats = [
    {
        title: 'Total Revenue',
        value: '$48,352',
        trend: '+12.5%',
        trendLabel: 'vs last month',
        trendUp: true,
        iconPath: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        iconBg: '#ede9fe',
        iconColor: '#6d5dfc',
        valueSuffix: '',
    },
    {
        title: 'Pending Invoices',
        value: '12',
        trend: '+3',
        trendLabel: 'from last week',
        trendUp: true,
        iconPath: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        iconBg: '#fef9c3',
        iconColor: '#ca8a04',
        valueSuffix: '',
    },
    {
        title: 'Active Clients',
        value: '84',
        trend: '+5',
        trendLabel: 'new this month',
        trendUp: true,
        iconPath: 'M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H7a2 2 0 01-2-2v-2a3 3 0 013-3h4a3 3 0 013 3v2a2 2 0 01-2 2m-2-5a3 3 0 11-6 0 3 3 0 016 0z',
        iconBg: '#dcfce7',
        iconColor: '#16a34a',
        valueSuffix: '',
    },
    {
        title: 'Overdue Amount',
        value: '$3,240',
        trend: '2',
        trendLabel: 'invoices overdue',
        trendUp: false,
        iconPath: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        iconBg: '#fee2e2',
        iconColor: '#dc2626',
        valueSuffix: '',
    },
];

const recentInvoices = [
    { id: '#INS-0120010', name: 'Emily Parker', email: 'emily@startupgrove.io', product: 'Bootstrap - Extended License', amount: '$999.00 USD', status: 'paid', dates: 'Feb 2 - Feb 10, 2025' },
    { id: '#INS-0120009', name: 'Michael Scott', email: 'michael@dundermifflin.com', product: 'CRM Dashboard - Regular License', amount: '$249.00 USD', status: 'pending', dates: 'Feb 5 - Feb 12, 2025' },
    { id: '#INS-0120008', name: 'Samantha Reed', email: 'samantha@clickandmine.com', product: 'Landing Page - Agency Pack', amount: '$349.00 USD', status: 'overdue', dates: 'Jan 10 - Jan 15, 2025' },
    { id: '#INS-0120007', name: 'Jonathan Lee', email: 'jonathan@beanflow.io', product: 'Task Manager - SaaS Version', amount: '$799.00 USD', status: 'draft', dates: 'Mar 1 - Mar 5, 2025' },
    { id: '#INS-0120006', name: 'Carlos Diaz', email: 'carlos@thereverwise.com', product: 'Admin Panel - Developer License', amount: '$1,199.00 USD', status: 'paid', dates: 'Mar 10 - Mar 15, 2025' },
];

const statusBadge = {
    paid: 'badge-success',
    pending: 'badge-warning',
    overdue: 'badge-danger',
    draft: 'badge-secondary',
};

const statusLabel = {
    paid: 'Paid',
    pending: 'Pending',
    overdue: 'Overdue',
    draft: 'Draft',
};

const monthlyData = [45, 52, 38, 65, 71, 58, 82, 69, 77, 85, 92, 78];
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const maxVal = Math.max(...monthlyData);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- ═══════ STATS GRID ═══════ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <div
                v-for="(stat, i) in stats"
                :key="stat.title"
                class="card p-5 animate-fade-in"
                :style="{ animationDelay: `${i * 60}ms` }"
            >
                <div class="flex items-start justify-between mb-4">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center flex-shrink-0"
                        :style="{ background: stat.iconBg }">
                        <svg class="h-5 w-5" :style="{ color: stat.iconColor }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                :d="stat.iconPath" />
                        </svg>
                    </div>
                </div>
                <p class="stat-label mb-1">{{ stat.title }}</p>
                <p class="stat-value">{{ stat.value }}</p>
                <div class="mt-3 flex items-center gap-1.5 text-xs">
                    <span :class="stat.trendUp ? 'text-emerald-600' : 'text-red-500'" class="font-semibold">
                        {{ stat.trendUp ? '↑' : '↓' }} {{ stat.trend }}
                    </span>
                    <span class="text-slate-400">{{ stat.trendLabel }}</span>
                </div>
            </div>
        </div>

        <!-- ═══════ CHART + TABLE ═══════ -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            <!-- Revenue Chart -->
            <div class="xl:col-span-2 card p-5 animate-fade-in">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">Revenue Overview</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Monthly performance, 2025</p>
                    </div>
                    <select class="input w-auto px-3 py-1.5 text-xs rounded-lg">
                        <option>Last 12 months</option>
                        <option>Last 6 months</option>
                        <option>Last 30 days</option>
                    </select>
                </div>

                <!-- Bar Chart -->
                <div class="h-48 flex items-end gap-1 px-1">
                    <div
                        v-for="(val, idx) in monthlyData"
                        :key="idx"
                        class="flex-1 flex flex-col items-center gap-1.5 group"
                    >
                        <div class="w-full rounded-t-lg relative overflow-hidden transition-all duration-500 cursor-pointer"
                            :style="{
                                height: `${(val / maxVal) * 100}%`,
                                background: 'linear-gradient(to top, #6d5dfc, #a78bfa)',
                                minHeight: '4px',
                                opacity: '0.85',
                            }">
                            <!-- Tooltip -->
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs rounded px-2 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                ${{ val }}k
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400">{{ months[idx] }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-slate-400">Total Revenue</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">$48,352</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Avg Monthly</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">$4,029</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Growth</p>
                        <p class="text-sm font-bold text-emerald-600 mt-0.5">+12.5%</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats / Donut placeholder -->
            <div class="card p-5 animate-fade-in">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Invoice Status</h2>
                <div class="space-y-3">
                    <div v-for="item in [
                        { label: 'Paid', value: 42, color: '#16a34a', bg: '#dcfce7' },
                        { label: 'Pending', value: 28, color: '#ca8a04', bg: '#fef9c3' },
                        { label: 'Overdue', value: 15, color: '#dc2626', bg: '#fee2e2' },
                        { label: 'Draft', value: 15, color: '#94a3b8', bg: '#f1f5f9' },
                    ]" :key="item.label">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full" :style="{ background: item.color }" />
                                <span class="text-xs text-slate-600">{{ item.label }}</span>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">{{ item.value }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full" :style="{ background: item.bg }">
                            <div class="h-1.5 rounded-full transition-all duration-700"
                                :style="{ width: item.value + '%', background: item.color }" />
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100">
                    <Link :href="route('forms.index')" class="btn btn-primary w-full justify-center text-xs py-2">
                        View All Forms
                    </Link>
                </div>
            </div>
        </div>

        <!-- ═══════ RECENT INVOICES TABLE ═══════ -->
        <div class="card mt-5 animate-fade-in overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-semibold text-slate-700">Recent Invoices</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest 5 transactions</p>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Dates</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="inv in recentInvoices" :key="inv.id">
                            <td>
                                <span class="font-mono text-xs font-medium text-primary-600">{{ inv.id }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-semibold"
                                        style="background: linear-gradient(135deg, #6d5dfc, #48cfad);">
                                        {{ inv.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ inv.name }}</p>
                                        <p class="text-xs text-slate-400">{{ inv.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-500">{{ inv.product }}</td>
                            <td>
                                <span class="text-sm font-semibold text-slate-700">{{ inv.amount }}</span>
                            </td>
                            <td>
                                <span :class="statusBadge[inv.status]" class="badge">
                                    {{ statusLabel[inv.status] }}
                                </span>
                            </td>
                            <td class="text-slate-400 text-xs">{{ inv.dates }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-slate-100 flex justify-between items-center">
                <p class="text-xs text-slate-400">Showing 1 to 5 of 10 invoices</p>
                <div class="flex gap-1">
                    <button class="px-2.5 py-1 rounded-lg text-xs text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">← Prev</button>
                    <button class="px-2.5 py-1 rounded-lg text-xs text-white transition-colors" style="background: #6d5dfc;">1</button>
                    <button class="px-2.5 py-1 rounded-lg text-xs text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">2</button>
                    <button class="px-2.5 py-1 rounded-lg text-xs text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Next →</button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
