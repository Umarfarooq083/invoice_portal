<script setup>
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    aprs: Object,
    filters: Object,
});

const sort = (field) => {
    let direction = 'asc';
    if (props.filters?.sort === field && props.filters?.direction === 'asc') {
        direction = 'desc';
    }
    router.get(route('aprs.index'), { ...props.filters, sort: field, direction }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const deleteModal = ref({ show: false, apr: null });
const deleteForm = useForm({});

function confirmDelete(apr) {
    deleteModal.value = { show: true, apr };
}

function destroyApr() {
    if (!deleteModal.value.apr) return;

    deleteForm.delete(route('aprs.destroy', deleteModal.value.apr.id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteModal.value = { show: false, apr: null };
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>

        <Head title="APR" />

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold text-slate-800">APR</h1>
            </div>
            <Link :href="route('aprs.create')" class="btn btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New APR
            </Link>
        </div>

        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th @click="sort('id')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    ID
                                    <span v-if="filters?.sort === 'id'" class="text-xs">{{ filters.direction === 'asc' ?
                                        '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('society_id')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">Block</div>
                            </th>
                            <th @click="sort('tracking_code')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">Tracking Code</div>
                            </th>
                            <th @click="sort('from_reg_no')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">App No</div>
                            </th>

                            <th @click="sort('client_name')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">Client Name</div>
                            </th>
                            <th @click="sort('dealer_name')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">Dealer</div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="apr in aprs.data" :key="apr.id">
                            <td>
                                <span class="font-mono text-xs font-semibold">{{ apr.id }}</span>
                            </td>
                            <td>{{ apr.block ? apr.block.name : '-' }}</td>
                            <td>{{ apr.tracking_code }}</td>
                            <td>{{ apr.from_reg_no || apr.reg_no }}</td>

                            <td>{{ apr.client_name }}</td>
                            <td>{{ apr?.dealer?.name || apr?.dealer_name }}</td>
                            <td>
                                <div class="flex gap-1">
                                    <button
                                        @click="confirmDelete(apr)"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                        title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="!aprs.data || aprs.data.length === 0" class="text-center py-16">
                <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">No APR records found</p>
                <p class="text-xs text-slate-400 mt-1">Add a new APR to get started</p>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
                <p class="text-xs text-slate-400">
                    Showing {{ aprs.from ?? 0 }} to {{ aprs.to ?? 0 }} of {{ aprs.total ?? 0 }} APR records
                </p>
                <Pagination v-if="aprs && aprs.links" :links="aprs.links" />
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="deleteModal.show" @close="deleteModal = { show: false, apr: null }">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0"
                    style="background: #fee2e2;">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Delete APR</h2>
                    <p class="text-xs text-slate-400">This action cannot be undone</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 mb-6 p-3 rounded-xl bg-red-50 border border-red-100">
                Are you sure you want to delete APR
                <span class="font-semibold text-red-600">#{{ deleteModal.apr?.id }}</span>?
            </p>
            <div class="flex justify-end gap-3">
                <SecondaryButton @click="deleteModal = { show: false, apr: null }">
                    Cancel
                </SecondaryButton>
                <DangerButton :disabled="deleteForm.processing" @click="destroyApr">
                    <span v-if="deleteForm.processing">Deleting...</span>
                    <span v-else>Delete</span>
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
