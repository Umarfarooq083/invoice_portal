<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

function debounce(fn, delay) {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

const props = defineProps({
    invoices: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const plot_type = ref(props.filters?.plot_type || '');

watch([search, plot_type], debounce(([newSearch, newPlotType]) => {
    router.get(route('invoices.index'), { search: newSearch, plot_type: newPlotType }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 300));
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

        <div class="card mb-4">
            <div class="flex flex-wrap items-end gap-3 p-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input class="input pl-9 text-sm" type="text" v-model="search"
                            placeholder="Search invoices by Reg No, Name, Tracking..." />
                    </div>
                </div>

                <!-- Plot Type -->
                <div class="min-w-[150px]">
                    <select class="input text-sm" v-model="plot_type">
                        <option value="">All Plot Types</option>
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Registration No</th>
                            <th>Downpayment</th>
                            <th>Plot Price</th>
                            <th>Size</th>
                            <th>Box No</th>
                            <th>Sr No</th>
                            <th>Tracking Code</th>
                            <th>Block</th>
                            <th>Received By</th>
                            <th>Booking Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice, index in invoices.data" :key="invoice.id">
                            <td>
                                <span class="font-mono text-xs font-semibold">
                                    {{ (invoices.current_page - 1) * invoices.per_page + index + 1 }}
                                </span>
                            </td>
                            <td class="text-slate-700 font-medium">
                                {{ invoice?.client_name }}
                            </td>
                            <td class="text-slate-700 font-medium">
                                {{ invoice?.reg_no }}
                            </td>
                            <td class="text-slate-500 text-sm">{{ invoice?.downpayment }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.plot_price }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.size }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.box_no }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.sr_no }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.tracking_code }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.block?.name ?? '-' }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice?.user?.name ?? '-' }}</td>
                            <td class="text-slate-500 text-sm">{{ invoice.created_at ? new Date(invoice.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '-' }}</td>
                            <td class="text-slate-500 text-sm">View</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="!invoices.data || invoices.data.length === 0" class="text-center py-16">
                <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
