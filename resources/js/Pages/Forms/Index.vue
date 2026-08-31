<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    forms: Object,
    filters: Object,
    dropdowns: Object,
});

const filterForm = useForm({
    search: props.filters?.search ?? '',
    society_id: props.filters?.society_id ?? '',
    reg_type: props.filters?.reg_type ?? '',
    size: props.filters?.size ?? '',
    sort: props.filters?.sort ?? 'id',
    direction: props.filters?.direction ?? 'desc',
});

const deleteModal = ref({ show: false, form: null });
const deleteForm = useForm({});

function formatCurrency(value) {
    if (!value) return '-';
    return new Intl.NumberFormat('en-PK', {
        style: 'currency',
        currency: 'PKR',
        maximumFractionDigits: 0,
    }).format(value);
}

function applyFilters() {
    filterForm.get(route('forms.index'), {
        preserveState: true,
        preserveScroll: true,
    });
}

function resetFilters() {
    filterForm.search = '';
    filterForm.society_id = '';
    filterForm.reg_type = '';
    filterForm.size = '';
    filterForm.sort = 'id';
    filterForm.direction = 'desc';
    applyFilters();
}

function sort(field) {
    if (filterForm.sort === field && filterForm.direction === 'asc') {
        filterForm.direction = 'desc';
    } else {
        filterForm.sort = field;
        filterForm.direction = 'asc';
    }
    applyFilters();
}

function confirmDelete(form) {
    deleteModal.value = { show: true, form };
}

function destroyForm() {
    if (!deleteModal.value.form) return;

    deleteForm.delete(route('forms.destroy', deleteModal.value.form.id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteModal.value = { show: false, form: null };
        },
    });
}

function statusBadge(status) {
    return status
        ? 'badge badge-success'
        : 'badge badge-secondary';
}
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Forms" />

        <!-- Page title row -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold text-slate-800">Forms</h1>
                <!-- <p class="text-xs text-slate-400 mt-0.5">Manage all application forms</p> -->
            </div>
            <Link :href="route('forms.create')" class="btn btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Form
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
                        <input class="input pl-9 text-sm" type="text" v-model="filterForm.search"
                            placeholder="Search Forms..." />
                    </div>
                </div>

                <!-- App Type -->
                <div class="min-w-[130px]">
                    <select class="input text-sm" v-model="filterForm.reg_type">
                        <option value="">All Types</option>
                        <option v-for="type in dropdowns.app_types" :key="type.id" :value="String(type.id)">
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <!-- Block -->
                <div class="min-w-[130px]">
                    <select class="input text-sm" v-model="filterForm.society_id">
                        <option value="">All Blocks</option>
                        <option v-for="block in dropdowns.blocks" :key="block.id" :value="String(block.id)">
                            {{ block.name }}
                        </option>
                    </select>
                </div>

                <!-- Size -->
                <div class="min-w-[120px]">
                    <select class="input text-sm" v-model="filterForm.size">
                        <option value="">All Sizes</option>
                        <option v-for="size in dropdowns.app_sizes" :key="size" :value="size">
                            {{ size }}
                        </option>
                    </select>
                </div>

                <!-- Filter By label area -->
                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-xs text-slate-400 whitespace-nowrap">Filter By:</span>
                    <SecondaryButton @click="resetFilters" class="text-xs py-1.5 px-3">
                        Reset
                    </SecondaryButton>
                    <PrimaryButton @click="applyFilters" :disabled="filterForm.processing" class="text-xs py-1.5 px-3">
                        Apply
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th @click="sort('form_no')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Form No
                                    <span v-if="filterForm.sort === 'form_no'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('client_name')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Client Name
                                    <span v-if="filterForm.sort === 'client_name'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('client_cnic')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Client Cnic
                                    <span v-if="filterForm.sort === 'client_cnic'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('tracking_code')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Tracking Code
                                    <span v-if="filterForm.sort === 'tracking_code'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('size')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Size
                                    <span v-if="filterForm.sort === 'size'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('box_no')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Box No
                                    <span v-if="filterForm.sort === 'box_no'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th @click="sort('plot_price')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Plot Price
                                    <span v-if="filterForm.sort === 'plot_price'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th>Dealer Name</th>
                            <th>Form Type</th>
                            <th>Block</th>
                            <th>Received By</th>
                            <th @click="sort('created_at')" class="cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center gap-1">
                                    Created At
                                    <span v-if="filterForm.sort === 'created_at'" class="text-xs">{{ filterForm.direction === 'asc' ? '↑' : '↓' }}</span>
                                    <span v-else class="text-xs text-slate-300">↕</span>
                                </div>
                            </th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="form in forms.data" :key="form.id">
                            <td>
                                <span class="font-mono text-xs font-semibold">{{ form?.form_no }}</span>
                            </td>
                            <td class="text-xs text-slate-400">
                                {{ form?.client_name }}
                            </td>
                            <td class="text-xs text-slate-400">
                                {{ form?.client_cnic }}
                            </td>
                            <td class="text-slate-500 text-sm">{{ form?.tracking_code }}</td>
                            <td class="text-slate-500 text-sm">{{ form?.size }}</td>
                            <td class="text-slate-500 text-sm">{{ form?.box_no }}</td>
                            <td>
                                <span class="text-sm font-semibold text-slate-700">{{ formatCurrency(form?.plot_price)
                                }}</span>
                            </td>
                            <td class="text-slate-500 text-sm">{{ form?.dealer?.name ?? '-' }}</td>
                            <td class="text-slate-500 text-sm">{{ form?.app_type?.name ?? '-' }}</td>
                            <td class="text-slate-500 text-sm">{{ form?.block?.name }}</td>
                            <td class="text-slate-500 text-sm">{{ form?.user?.name }}</td>
                            <td>
                                {{ form.created_at ? new Date(form.created_at).toLocaleDateString('en-US', {
                                    month:
                                        'short', day: 'numeric', year: 'numeric'
                                }) : '-' }}
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    <a :href="route('forms.show', form.id)"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg text-slate-400 hover:bg-primary-50 hover:text-primary-600 transition-colors"
                                        title="View">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <!-- Edit -->
                                    <!-- <Link :href="route('forms.edit', form.id)"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                                        title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link> -->
                                    <!-- Delete -->
                                    <!-- <button @click="confirmDelete(form)"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                        title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button> -->
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="!forms.data || forms.data.length === 0" class="text-center py-16">
                <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-7 w-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">No forms found</p>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your filters</p>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
                <p class="text-xs text-slate-400">
                    Showing {{ forms.from ?? 0 }} to {{ forms.to ?? 0 }} of {{ forms.total ?? 0 }} forms
                </p>
                <Pagination v-if="forms" :links="forms.links" />
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="deleteModal.show" @close="deleteModal = { show: false, form: null }">
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
                    <h2 class="text-base font-bold text-slate-800">Delete Form</h2>
                    <p class="text-xs text-slate-400">This action cannot be undone</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 mb-6 p-3 rounded-xl bg-red-50 border border-red-100">
                Are you sure you want to delete form
                <span class="font-semibold text-red-600">#{{ deleteModal.form?.form_no }}</span>?
            </p>
            <div class="flex justify-end gap-3">
                <SecondaryButton @click="deleteModal = { show: false, form: null }">
                    Cancel
                </SecondaryButton>
                <DangerButton :disabled="deleteForm.processing" @click="destroyForm">
                    <span v-if="deleteForm.processing">Deleting...</span>
                    <span v-else>Delete</span>
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
