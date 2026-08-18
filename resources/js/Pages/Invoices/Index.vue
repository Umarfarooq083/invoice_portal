<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    invoices: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Invoices" />

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold text-slate-800">Invoices</h1>
            </div>
            <Link :href="route('invoices.create')" class="btn btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Invoice
            </Link>
        </div>

        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reg No</th>
                            <th>Client Name</th>
                            <th>Plot Type</th>
                            <th>Downpayment</th>
                            <th>Plot Price</th>
                            <th>Tracking Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoices.data" :key="invoice.id">
                            <td>
                                <span class="font-mono text-xs font-semibold">{{ invoice.id }}</span>
                            </td>
                            <td class="text-slate-700 font-medium">
                                {{ invoice.reg_no }}
                            </td>
                            <td class="text-slate-700 font-medium">
                                {{ invoice.client_name }}    
                            </td>
                            <td class="text-slate-500 text-sm">{{ invoice.plot_type }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice.downpayment }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice.plot_price }}</td>
                            <td class="text-slate-500 text-sm">
                                <span class="badge badge-primary">{{ invoice.tracking_code }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="!invoices.data || invoices.data.length === 0" class="text-center py-16">
                <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">No invoices found</p>
                <p class="text-xs text-slate-400 mt-1">Add a new invoice to get started</p>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
                <p class="text-xs text-slate-400">
                    Showing {{ invoices.from ?? 0 }} to {{ invoices.to ?? 0 }} of {{ invoices.total ?? 0 }} invoices
                </p>
                <Pagination v-if="invoices && invoices.links" :links="invoices.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
