<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    form: Object,
    dropdowns: Object,
});

const form = useForm({
    form_no: props.form.form_no ?? '',
    tracking_code: props.form.tracking_code ?? '',
    plot_price: props.form.plot_price ?? '',
    down_payment: props.form.down_payment ?? '',
    form_type: props.form.form_type ?? '',
    block_id: props.form.block_id ?? '',
    size: props.form.size ?? '',
    client_name: props.form.client_name ?? '',
    contact: props.form.contact ?? '',
    client_cnic: props.form.client_cnic ?? '',
    box_no: props.form.box_no ?? '',
    sr_no: props.form.sr_no ?? '',
    submitted_by: props.form.submitted_by ?? '',
    submitter_cnic: props.form.submitter_cnic ?? '',
    deposite_slip_no: props.form.deposite_slip_no ?? '',
    dealer_id: props.form.dealer_id ?? '',
});

function submit() {
    form.put(route('forms.update', props.form.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Edit Form</h1>
                    <p class="page-subtitle">Update form #{{ form.form_no }}</p>
                </div>
                <SecondaryButton :href="route('forms.index')">
                    Back to List
                </SecondaryButton>
            </div>
        </template>

        <Head title="Edit Form" />

        <div class="card p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                     <div class="lg:col-span-1">
                        <InputLabel value="Block" />
                        <select
                            class="input"
                            v-model.number="form.block_id"
                        >
                            <option value="">Select an Option</option>
                            <option
                                v-for="block in dropdowns.blocks"
                                :key="block.id"
                                :value="block.id"
                            >
                                {{ block.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.block_id" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="App Type" />
                        <select
                            class="input"
                            v-model.number="form.form_type"
                        >
                            <option value="">Select an Option</option>
                            <option
                                v-for="type in dropdowns.app_types"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.form_type" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Tracking Code" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.tracking_code"
                            placeholder="Auto-generated"
                            readonly
                            style="background-color: #f5f5f5;"
                        />
                        <InputError :message="form.errors.tracking_code" />
                    </div>
                    
                    <div class="lg:col-span-1">
                        <InputLabel value="Form No" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.form_no"
                            placeholder="Enter form no"
                        />
                        <InputError :message="form.errors.form_no" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Plot Price" />
                        <input
                            class="input"
                            type="number"
                            v-model.number="form.plot_price"
                            placeholder="0"
                        />
                        <InputError :message="form.errors.plot_price" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Down Payment" />
                        <input
                            class="input"
                            type="number"
                            v-model.number="form.down_payment"
                            placeholder="0"
                        />
                        <InputError :message="form.errors.down_payment" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="App Size" />
                        <select
                            class="input"
                            v-model="form.size"
                        >
                            <option value="">Select Plot Size</option>
                            <option
                                v-for="size in dropdowns.app_sizes"
                                :key="size"
                                :value="size"
                            >
                                {{ size }}
                            </option>
                        </select>
                        <InputError :message="form.errors.size" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Client Name" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.client_name"
                            placeholder="Enter client name"
                        />
                        <InputError :message="form.errors.client_name" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Contact" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.contact"
                            placeholder="Enter contact"
                        />
                        <InputError :message="form.errors.contact" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Client Cnic" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.client_cnic"
                            placeholder="XXXXX-XXXXXXX-XX"
                        />
                        <InputError :message="form.errors.client_cnic" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Box No" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.box_no"
                            placeholder="Auto-generated"
                            readonly
                            style="background-color: #f5f5f5;"
                        />
                        <InputError :message="form.errors.box_no" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Sr No" />
                        <input
                            class="input"
                            type="number"
                            v-model.number="form.sr_no"
                            placeholder="0"
                        />
                        <InputError :message="form.errors.sr_no" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Submitted By" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.submitted_by"
                            placeholder="Enter name"
                        />
                        <InputError :message="form.errors.submitted_by" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Submitter Cnic" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.submitter_cnic"
                            placeholder="XXXXX-XXXXXXX-XX"
                        />
                        <InputError :message="form.errors.submitter_cnic" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Deposite Slip No" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.deposite_slip_no"
                            placeholder="Enter deposit slip no"
                        />
                        <InputError :message="form.errors.deposite_slip_no" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Dealer Id" />
                        <input
                            class="input"
                            type="number"
                            v-model.number="form.dealer_id"
                            placeholder="0"
                        />
                        <InputError :message="form.errors.dealer_id" />
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-dark-200 pt-6">
                    <SecondaryButton :href="route('forms.show', props.form.id)">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing">
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Update Form</span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
