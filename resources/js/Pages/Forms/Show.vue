<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    form: Object,
});

const deleteModal = ref({ show: false });
const deleteForm = useForm({});

function confirmDelete() {
    deleteModal.value = { show: true };
}

function destroyForm() {
    deleteForm.delete(route('forms.destroy', props.form.id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteModal.value = { show: false };
        },
    });
}

function formatCurrency(value) {
    if (!value) return '-';
    return new Intl.NumberFormat('en-PK', {
        style: 'currency',
        currency: 'PKR',
        maximumFractionDigits: 0,
    }).format(value);
}

const detailRows = computed(() => [
    { label: 'Plot Price', value: formatCurrency(props.form.plot_price) },
    { label: 'Form No', value: props.form.form_no },
    { label: 'Tracking Code', value: props.form.tracking_code },
    { label: 'Down Payment', value: formatCurrency(props.form.down_payment) },
    { label: 'App Type', value: props.form.app_type?.name ?? '-' },
    { label: 'Block', value: props.form.block?.name ?? '-' },
    { label: 'App Size', value: props.form.size ?? '-' },
    { label: 'Phase Id', value: props.form.phase?.name ?? '-' },
    { label: 'Client Name', value: props.form.client_name },
    { label: 'Contact', value: props.form.contact },
    { label: 'Client Cnic', value: props.form.client_cnic },
    { label: 'Address', value: props.form.address },
    { label: 'Box No', value: props.form.box_no },
    { label: 'Sr No', value: props.form.sr_no },
    { label: 'Submitted By', value: props.form.submitted_by ?? '-' },
    { label: 'Submitter Cnic', value: props.form.submitter_cnic ?? '-' },
    { label: 'Deposite Slip No', value: props.form.deposite_slip_no },
    { label: 'Dealer Id', value: props.form.dealer_id ?? '-' },
    { label: 'Submitted By (User)', value: props.form.user?.name ?? '-' },
    { label: 'QR Code', value: props.form.qr_code ?? '-' },
    { label: 'Created At', value: props.form.created_at ? new Date(props.form.created_at).toLocaleString() : '-' },
]);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Form #{{ form.form_no }}</h1>
                    <p class="page-subtitle">
                        Tracking Code:
                        <span class="font-semibold text-dark-900">{{ form.tracking_code }}</span>
                    </p>
                </div>
                <div class="flex gap-3">
                    <SecondaryButton :href="route('forms.edit', form.id)">
                        Edit
                    </SecondaryButton>
                    <DangerButton @click="confirmDelete">
                        Delete
                    </DangerButton>
                    <PrimaryButton :href="route('forms.index')">
                        Back to List
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <Head title="Form Details" />

        <div class="card p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-lg font-semibold text-dark-900 mb-4">Form Information</h2>
                    <dl class="grid grid-cols-[140px_1fr] gap-3">
                        <div
                            v-for="row in detailRows"
                            :key="row.label"
                            class="contents"
                        >
                            <dt class="text-sm text-dark-500">{{ row.label }}</dt>
                            <dd class="text-sm font-medium text-dark-900 break-words">
                                {{ row.value }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-dark-900">Status</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-dark-50">
                            <span class="text-sm text-dark-600">Live Status</span>
                            <span
                                :class="form.is_create_live ? 'badge badge-success' : 'badge badge-secondary'"
                            >
                                {{ form.is_create_live ? 'Live' : 'Pending' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-dark-50">
                            <span class="text-sm text-dark-600">Member Transfer</span>
                            <span
                                :class="form.is_member_transfer ? 'badge badge-success' : 'badge badge-secondary'"
                            >
                                {{ form.is_member_transfer ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="form.qr_code"
                        class="p-4 rounded-xl bg-dark-50 text-center"
                    >
                        <p class="text-sm text-dark-500 mb-2">QR Code</p>
                        <p class="font-mono text-xs text-dark-900 break-all">
                            {{ form.qr_code }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <Modal
            :show="deleteModal.show"
            @close="deleteModal = { show: false }"
        >
            <div class="p-6">
                <h2 class="text-lg font-bold text-dark-900 mb-4">Confirm Delete</h2>
                <p class="text-dark-600 mb-6">
                    Are you sure you want to delete form
                    <span class="font-semibold">#{{ form.form_no }}</span>?
                    This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="deleteModal = { show: false }">
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
    </AuthenticatedLayout>
</template>
