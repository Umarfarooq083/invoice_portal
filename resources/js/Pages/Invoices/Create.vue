<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    box_no: {
        type: String,
        required: true
    },
    next_sr_no: {
        type: Number,
        required: true
    }
});

const form = useForm({
    search_open_reg: '', // Not in DB, but requested in UI
    security_code: '',
    size: '',
    reg_no: '',
    plot_type: '',
    downpayment: '',
    plot_price: '',
    client_name: '',
    contact: '',
    address: '',
    client_cnic: '',
    box_no: props.box_no,
    sr_no: props.next_sr_no,
    tracking_code: '',
    received_by: '',
    dealer_phone: '',
    submitter_cnic: '',
    dealer_id: '',
});

const submit = () => {
    form.post(route('invoices.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create Invoice" />

        <div class="max-w-9xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <Link :href="route('invoices.index')" 
                    class="h-8 w-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Create Invoice</h1>
            </div>

            <div class="card p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Search Open Reg -->
                        <div>
                            <InputLabel for="search_open_reg" value="Search Open Reg" />
                            <TextInput id="search_open_reg" type="text" class="mt-1 block w-full" v-model="form.search_open_reg" />
                            <InputError class="mt-2" :message="form.errors.search_open_reg" />
                        </div>

                        <!-- Security Code -->
                        <div>
                            <InputLabel for="security_code" value="Security Code" />
                            <TextInput id="security_code" type="text" class="mt-1 block w-full" v-model="form.security_code" />
                            <InputError class="mt-2" :message="form.errors.security_code" />
                        </div>

                        <!-- Size -->
                        <div>
                            <InputLabel for="size" value="Size" />
                            <TextInput id="size" type="text" class="mt-1 block w-full" v-model="form.size" />
                            <InputError class="mt-2" :message="form.errors.size" />
                        </div>

                        <!-- Reg No -->
                        <div>
                            <InputLabel for="reg_no" value="Reg No" />
                            <TextInput id="reg_no" type="text" class="mt-1 block w-full" v-model="form.reg_no" />
                            <InputError class="mt-2" :message="form.errors.reg_no" />
                        </div>

                        <!-- Plot Type -->
                        <div>
                            <InputLabel for="plot_type" value="Plot Type" />
                            <TextInput id="plot_type" type="text" class="mt-1 block w-full" v-model="form.plot_type" />
                            <InputError class="mt-2" :message="form.errors.plot_type" />
                        </div>

                        <!-- Downpayment -->
                        <div>
                            <InputLabel for="downpayment" value="Downpayment" />
                            <TextInput id="downpayment" type="number" step="0.01" class="mt-1 block w-full" v-model="form.downpayment" />
                            <InputError class="mt-2" :message="form.errors.downpayment" />
                        </div>

                        <!-- Plot Price -->
                        <div>
                            <InputLabel for="plot_price" value="Plot Price" />
                            <TextInput id="plot_price" type="number" step="0.01" class="mt-1 block w-full" v-model="form.plot_price" />
                            <InputError class="mt-2" :message="form.errors.plot_price" />
                        </div>

                        <!-- Client Name -->
                        <div>
                            <InputLabel for="client_name" value="Client Name" />
                            <TextInput id="client_name" type="text" class="mt-1 block w-full" v-model="form.client_name" />
                            <InputError class="mt-2" :message="form.errors.client_name" />
                        </div>

                        <!-- Contact -->
                        <div>
                            <InputLabel for="contact" value="Contact" />
                            <TextInput id="contact" type="text" class="mt-1 block w-full" v-model="form.contact" />
                            <InputError class="mt-2" :message="form.errors.contact" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <InputLabel for="address" value="Address" />
                            <TextInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <!-- Client Cnic -->
                        <div>
                            <InputLabel for="client_cnic" value="Client Cnic" />
                            <TextInput id="client_cnic" type="text" class="mt-1 block w-full" v-model="form.client_cnic" />
                            <InputError class="mt-2" :message="form.errors.client_cnic" />
                        </div>

                        <!-- Box No -->
                        <div>
                            <InputLabel for="box_no" value="Box No" />
                            <TextInput id="box_no" type="number" class="mt-1 block w-full bg-slate-50 cursor-not-allowed text-slate-500" v-model="form.box_no" readonly required />
                            <InputError class="mt-2" :message="form.errors.box_no" />
                        </div>

                        <!-- Sr No -->
                        <div>
                            <InputLabel for="sr_no" value="Sr No" />
                            <TextInput id="sr_no" type="text" class="mt-1 block w-full bg-slate-50 cursor-not-allowed text-slate-500" v-model="form.sr_no" readonly required />
                            <InputError class="mt-2" :message="form.errors.sr_no" />
                        </div>

                        <!-- Tracking Code -->
                        <div>
                            <InputLabel for="tracking_code" value="Tracking Code" />
                            <TextInput id="tracking_code" type="text" class="mt-1 block w-full" v-model="form.tracking_code" required />
                            <InputError class="mt-2" :message="form.errors.tracking_code" />
                        </div>

                        <!-- Submitted By -->
                        <div>
                            <InputLabel for="received_by" value="Submitted By (ID)" />
                            <TextInput id="received_by" type="number" class="mt-1 block w-full" v-model="form.received_by" required />
                            <InputError class="mt-2" :message="form.errors.received_by" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <InputLabel for="dealer_phone" value="Phone" />
                            <TextInput id="dealer_phone" type="text" class="mt-1 block w-full" v-model="form.dealer_phone" required />
                            <InputError class="mt-2" :message="form.errors.dealer_phone" />
                        </div>

                        <!-- Submitter Cnic -->
                        <div>
                            <InputLabel for="submitter_cnic" value="Submitter Cnic" />
                            <TextInput id="submitter_cnic" type="text" class="mt-1 block w-full" v-model="form.submitter_cnic" />
                            <InputError class="mt-2" :message="form.errors.submitter_cnic" />
                        </div>

                        <!-- Dealer ID -->
                        <div>
                            <InputLabel for="dealer_id" value="Dealer ID" />
                            <TextInput id="dealer_id" type="number" class="mt-1 block w-full" v-model="form.dealer_id" />
                            <InputError class="mt-2" :message="form.errors.dealer_id" />
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <Link :href="route('invoices.index')"
                            class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                            Cancel
                        </Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save Invoice
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
