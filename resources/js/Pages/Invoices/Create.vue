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
    search_open_reg: '',
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

        <div class="max-w-9xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('invoices.index')"
                            class="btn-icon rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Create Invoice</h1>
                    </div>
                </div>


            </div>

            <form @submit.prevent="submit" class="space-y-8 animate-slide-up stagger-1">

                <!-- Section 1: Property & Registration Details -->
                <div class="card overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white"
                                style="background: var(--color-primary);">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-800">Property & Registration</h2>
                        </div>
                        <!-- <span class="badge badge-secondary">Step 1</span> -->
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Search Open Reg -->
                        <div class="col-span-full lg:col-span-1">
                            <InputLabel for="search_open_reg" value="Search Open Reg" class="label" />
                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <TextInput id="search_open_reg" type="text" class="pl-10 mt-0"
                                    v-model="form.search_open_reg" placeholder="Scan or enter code..." />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.search_open_reg" />
                        </div>

                        <!-- Reg No -->
                        <div>
                            <InputLabel for="reg_no" value="Reg Number" class="label" />
                            <TextInput id="reg_no" type="text" class="mt-1" v-model="form.reg_no"
                                placeholder="REG-10492" />
                            <InputError class="mt-1.5" :message="form.errors.reg_no" />
                        </div>

                        <!-- Security Code -->
                        <div>
                            <InputLabel for="security_code" value="Security Code" class="label" />
                            <TextInput id="security_code" type="text" class="mt-1" v-model="form.security_code"
                                placeholder="SEC-XXXX" />
                            <InputError class="mt-1.5" :message="form.errors.security_code" />
                        </div>

                        <!-- Size -->
                        <div>
                            <InputLabel for="size" value="Plot Size" class="label" />
                            <TextInput id="size" type="text" class="mt-1" v-model="form.size"
                                placeholder="5 Marla, 10 Marla" />
                            <InputError class="mt-1.5" :message="form.errors.size" />
                        </div>

                        <!-- Plot Type -->
                        <div>
                            <InputLabel for="plot_type" value="Plot Category" class="label" />
                            <TextInput id="plot_type" type="text" class="mt-1" v-model="form.plot_type"
                                placeholder="Residential / Commercial" />
                            <InputError class="mt-1.5" :message="form.errors.plot_type" />
                        </div>

                        <!-- Downpayment -->
                        <div>
                            <InputLabel for="downpayment" value="Downpayment" class="label" />
                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                <TextInput id="downpayment" type="number" step="0.01" class="pl-12 mt-0"
                                    v-model="form.downpayment" placeholder="0.00" />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.downpayment" />
                        </div>

                        <!-- Total Plot Price -->
                        <div>
                            <InputLabel for="plot_price" value="Total Plot Price" class="label" />
                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                <TextInput id="plot_price" type="number" step="0.01"
                                    class="pl-12 mt-0 font-semibold text-slate-900" v-model="form.plot_price"
                                    placeholder="0.00" />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.plot_price" />
                        </div>

                        <!-- BOX NO -->
                        <div>
                            <InputLabel for="box_no" value="Total Plot Price" class="label" />
                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                <TextInput id="box_no" type="number" step="0.01"
                                    class="pl-12 mt-0 font-semibold text-slate-900" v-model="form.box_no" readonly
                                    disabled placeholder="0.00" />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.box_no" />
                        </div>

                        <!-- SR NO -->
                        <div>
                            <InputLabel for="sr_no" value="Total Plot Price" class="label" />
                            <div class="relative mt-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                <TextInput id="sr_no" type="number" step="0.01"
                                    class="pl-12 mt-0 font-semibold text-slate-900" v-model="form.sr_no" readonly
                                    disabled placeholder="0.00" />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.sr_no" />
                        </div>


                    </div>

                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white"
                                style="background: var(--color-primary);">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-800">Client Details</h2>
                        </div>
                        <!-- <span class="badge badge-secondary">Step 2</span> -->
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Client Name -->
                        <div>
                            <InputLabel for="client_name" value="Full Name" class="label" />
                            <TextInput id="client_name" type="text" class="mt-1" v-model="form.client_name"
                                placeholder="Muhammad Ali" />
                            <InputError class="mt-1.5" :message="form.errors.client_name" />
                        </div>

                        <!-- Contact -->
                        <div>
                            <InputLabel for="contact" value="Phone Number" class="label" />
                            <TextInput id="contact" type="text" class="mt-1" v-model="form.contact"
                                placeholder="+92 300 1234567" />
                            <InputError class="mt-1.5" :message="form.errors.contact" />
                        </div>

                        <!-- Client CNIC -->
                        <div>
                            <InputLabel for="client_cnic" value="Client CNIC" class="label" />
                            <TextInput id="client_cnic" type="text" class="mt-1" v-model="form.client_cnic"
                                placeholder="35201-1234567-1" />
                            <InputError class="mt-1.5" :message="form.errors.client_cnic" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <InputLabel for="address" value="Residential Address" class="label" />
                            <TextInput id="address" type="text" class="mt-1" v-model="form.address"
                                placeholder="House #, Street, Block, City" />
                            <InputError class="mt-1.5" :message="form.errors.address" />
                        </div>
                    </div>

                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white"
                                style="background: var(--color-primary);">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-800">Dealer & System Meta</h2>
                        </div>
                        <!-- <span class="badge badge-secondary">Step 3</span> -->
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Tracking Code -->
                        <div>
                            <InputLabel for="tracking_code" value="Tracking Code *" class="label" />
                            <TextInput id="tracking_code" type="text" class="mt-1" v-model="form.tracking_code"
                                placeholder="TRK-99201" required />
                            <InputError class="mt-1.5" :message="form.errors.tracking_code" />
                        </div>

                        <!-- Submitted By ID -->
                        <div>
                            <InputLabel for="received_by" value="Submitted By (User ID) *" class="label" />
                            <TextInput id="received_by" type="number" class="mt-1" v-model="form.received_by"
                                placeholder="User ID #" required />
                            <InputError class="mt-1.5" :message="form.errors.received_by" />
                        </div>

                        <!-- Submitter CNIC -->
                        <div>
                            <InputLabel for="submitter_cnic" value="Submitter CNIC" class="label" />
                            <TextInput id="submitter_cnic" type="text" class="mt-1" v-model="form.submitter_cnic"
                                placeholder="35201-7654321-9" />
                            <InputError class="mt-1.5" :message="form.errors.submitter_cnic" />
                        </div>

                        <!-- Dealer ID -->
                        <div>
                            <InputLabel for="dealer_id" value="Dealer ID" class="label" />
                            <TextInput id="dealer_id" type="number" class="mt-1" v-model="form.dealer_id"
                                placeholder="DLR-0042" />
                            <InputError class="mt-1.5" :message="form.errors.dealer_id" />
                        </div>

                        <!-- Dealer Phone -->
                        <div>
                            <InputLabel for="dealer_phone" value="Dealer Phone *" class="label" />
                            <TextInput id="dealer_phone" type="text" class="mt-1" v-model="form.dealer_phone"
                                placeholder="+92 300 0000000" required />
                            <InputError class="mt-1.5" :message="form.errors.dealer_phone" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 pt-4 pb-12 mr-7">
                        <Link :href="route('invoices.index')" class="btn-secondary btn-lg">
                            Cancel
                        </Link>

                        <button type="submit" :disabled="form.processing" class="btn-primary btn-lg min-w-[160px]">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ form.processing ? 'Saving...' : 'Save Invoice' }}</span>
                        </button>
                    </div>

                </div>

                <!-- Form Action Buttons -->


            </form>
        </div>
    </AuthenticatedLayout>
</template>