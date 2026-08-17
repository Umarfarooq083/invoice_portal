<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
    block_id: props.filters?.block_id ?? '',
    phase_id: props.filters?.phase_id ?? '',
    form_type: props.filters?.form_type ?? '',
    size: props.filters?.size ?? '',
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
    filterForm.block_id = '';
    filterForm.phase_id = '';
    filterForm.form_type = '';
    filterForm.size = '';
    filterForm.get(route('forms.index'), {
        preserveState: true,
        preserveScroll: true,
    });
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
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Forms</h1>
                    <p class="page-subtitle">Manage all main form records</p>
                </div>
                <Link :href="route('forms.create')" class="btn btn-primary">
                    Add New Form
                </Link>
            </div>
        </template>

        <Head title="Forms" />

        <div class="card p-6 mb-6">
            <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <InputLabel value="Search" />
                    <input
                        class="input"
                        type="text"
                        v-model="filterForm.search"
                        placeholder="Search by client, CNIC, form no..."
                    />
                </div>

                <div>
                    <InputLabel value="App Type" />
                    <select class="input" v-model="filterForm.form_type">
                        <option value="">All</option>
                        <option
                            v-for="type in dropdowns.app_types"
                            :key="type.id"
                            :value="String(type.id)"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <InputLabel value="Block" />
                    <select class="input" v-model="filterForm.block_id">
                        <option value="">All</option>
                        <option
                            v-for="block in dropdowns.blocks"
                            :key="block.id"
                            :value="String(block.id)"
                        >
                            {{ block.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <InputLabel value="App Size" />
                    <select class="input" v-model="filterForm.size">
                        <option value="">All</option>
                        <option
                            v-for="size in dropdowns.app_sizes"
                            :key="size"
                            :value="size"
                        >
                            {{ size }}
                        </option>
                    </select>
                </div>

                <div>
                    <InputLabel value="Phase Id" />
                    <select class="input" v-model="filterForm.phase_id">
                        <option value="">All</option>
                        <option
                            v-for="phase in dropdowns.phases"
                            :key="phase.id"
                            :value="String(phase.id)"
                        >
                            {{ phase.name }}
                        </option>
                    </select>
                </div>
            </form>

            <div class="mt-4 flex justify-end gap-3">
                <SecondaryButton @click="resetFilters">
                    Reset
                </SecondaryButton>
                <PrimaryButton @click="applyFilters" :disabled="filterForm.processing">
                    Filter
                </PrimaryButton>
            </div>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Form No</th>
                            <th>Client Name</th>
                            <th>Tracking Code</th>
                            <th>Phase</th>
                            <th>Block</th>
                            <th>App Type</th>
                            <th>Plot Size</th>
                            <th>Plot Price</th>
                            <th>Down Payment</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="form in forms.data"
                            :key="form.id"
                        >
                            <td>{{ form.form_no }}</td>
                            <td>{{ form.client_name }}</td>
                            <td>{{ form.tracking_code }}</td>
                            <td>{{ form.phase?.name ?? '-' }}</td>
                            <td>{{ form.block?.name ?? '-' }}</td>
                            <td>{{ form.app_type?.name ?? '-' }}</td>
                            <td>{{ form.size }}</td>
                            <td>{{ formatCurrency(form.plot_price) }}</td>
                            <td>{{ formatCurrency(form.down_payment) }}</td>
                            <td>
                                <span :class="statusBadge(form.is_create_live)">
                                    {{ form.is_create_live ? 'Live' : 'Pending' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <a
                                        :href="route('forms.show', form.id)"
                                        class="text-sm text-dark-600 hover:text-primary-600"
                                    >
                                        View
                                    </a>
                                    <Link :href="route('forms.edit', form.id)"
                                        class="text-sm text-dark-600 hover:text-primary-600"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="confirmDelete(form)"
                                        class="text-sm text-danger-600 hover:text-danger-700"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                v-if="forms.data && forms.data.length === 0"
                class="text-center py-12 text-dark-500"
            >
                No forms found.
            </div>

            <div
                v-if="forms.meta && forms.meta.total > 0"
                class="px-6 py-4 border-t border-dark-200"
            >
                <div class="flex items-center justify-between text-sm text-dark-500">
                    <div>
                        Showing {{ forms.meta.from }} - {{ forms.meta.to }} of
                        {{ forms.meta.total }} results
                    </div>
                <div class="flex items-center gap-2">
                    <template v-if="forms.links">
                        <a
                            v-for="link in forms.links"
                            :key="link.label"
                            :href="link.url || undefined"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1 rounded-xl text-sm font-medium transition-all',
                                link.active
                                    ? 'bg-primary-600 text-white'
                                    : 'text-dark-700 hover:bg-dark-100',
                                !link.url && 'opacity-50 cursor-not-allowed',
                            ]"
                        />
                    </template>
                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal
        :show="deleteModal.show"
        @close="deleteModal = { show: false, form: null }"
    >
        <div class="p-6">
            <h2 class="text-lg font-bold text-dark-900 mb-4">Confirm Delete</h2>
            <p class="text-dark-600 mb-6">
                Are you sure you want to delete form
                <span class="font-semibold">#{{ deleteModal.form?.form_no }}</span>?
            </p>
            <div class="flex justify-end gap-3">
                <SecondaryButton @click="deleteModal = { show: false, form: null }">
                    Cancel
                </SecondaryButton>
                <DangerButton
                    :disabled="deleteForm.processing"
                    @click="destroyForm"
                >
                    <span v-if="deleteForm.processing">Deleting...</span>
                    <span v-else>Delete</span>
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
