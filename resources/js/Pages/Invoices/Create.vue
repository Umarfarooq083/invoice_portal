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

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <Link 
                        :href="route('invoices.index')" 
                        class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-white border border-slate-200 text-slate-600 shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create Invoice</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Generate and record a new property invoice.</p>
                    </div>
                </div>

                <!-- Read-Only Badge Info -->
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-slate-100 border border-slate-200/80 text-xs font-medium text-slate-700">
                        <span class="text-slate-400">Box:</span>
                        <span class="font-semibold text-slate-900">{{ form.box_no }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-indigo-50 border border-indigo-100 text-xs font-medium text-indigo-700">
                        <span class="text-indigo-400">Sr #:</span>
                        <span class="font-bold text-indigo-900">{{ form.sr_no }}</span>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Section 1: Property & Registration Details -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800">Property & Registration</h2>
                            <p class="text-xs text-slate-500">File allocation and payment terms.</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-200/60 font-mono text-slate-600">Step 1</span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Search Open Reg -->
                        <div>
                            <InputLabel for="search_open_reg" value="Search Open Reg" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <div class="relative mt-1.5">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <TextInput 
                                    id="search_open_reg" 
                                    type="text" 
                                    class="pl-9 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                    v-model="form.search_open_reg" 
                                    placeholder="Scan or enter code..." 
                                />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.search_open_reg" />
                        </div>

                        <!-- Reg No -->
                        <div>
                            <InputLabel for="reg_no" value="Reg Number" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="reg_no" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.reg_no" 
                                placeholder="REG-10492" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.reg_no" />
                        </div>

                        <!-- Security Code -->
                        <div>
                            <InputLabel for="security_code" value="Security Code" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="security_code" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.security_code" 
                                placeholder="SEC-XXXX" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.security_code" />
                        </div>

                        <!-- Size -->
                        <div>
                            <InputLabel for="size" value="Plot Size" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="size" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.size" 
                                placeholder="5 Marla, 10 Marla, 1 Kanal" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.size" />
                        </div>

                        <!-- Plot Type -->
                        <div>
                            <InputLabel for="plot_type" value="Plot Category" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="plot_type" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.plot_type" 
                                placeholder="Residential / Commercial" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.plot_type" />
                        </div>

                        <!-- Downpayment -->
                        <div>
                            <InputLabel for="downpayment" value="Downpayment" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <div class="relative mt-1.5">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-medium text-xs">PKR</span>
                                <TextInput 
                                    id="downpayment" 
                                    type="number" 
                                    step="0.01" 
                                    class="pl-12 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                    v-model="form.downpayment" 
                                    placeholder="0.00" 
                                />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.downpayment" />
                        </div>

                        <!-- Total Plot Price -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <InputLabel for="plot_price" value="Total Plot Price" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <div class="relative mt-1.5 max-w-sm">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-medium text-xs">PKR</span>
                                <TextInput 
                                    id="plot_price" 
                                    type="number" 
                                    step="0.01" 
                                    class="pl-12 block w-full rounded-xl border-slate-200 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500/20" 
                                    v-model="form.plot_price" 
                                    placeholder="0.00" 
                                />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.plot_price" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Client Information -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800">Client Details</h2>
                            <p class="text-xs text-slate-500">Beneficiary identification & contact information.</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-200/60 font-mono text-slate-600">Step 2</span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Client Name -->
                        <div>
                            <InputLabel for="client_name" value="Full Name" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="client_name" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.client_name" 
                                placeholder="Muhammad Ali" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.client_name" />
                        </div>

                        <!-- Contact -->
                        <div>
                            <InputLabel for="contact" value="Phone Number" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="contact" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.contact" 
                                placeholder="+92 300 1234567" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.contact" />
                        </div>

                        <!-- Client CNIC -->
                        <div>
                            <InputLabel for="client_cnic" value="Client CNIC" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="client_cnic" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.client_cnic" 
                                placeholder="35201-1234567-1" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.client_cnic" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <InputLabel for="address" value="Residential Address" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="address" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.address" 
                                placeholder="House #, Street, Block, City" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.address" />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Dealer & Submission Metadata -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800">Dealer & System Meta</h2>
                            <p class="text-xs text-slate-500">Internal tracking & dealer references.</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-200/60 font-mono text-slate-600">Step 3</span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- Tracking Code -->
                        <div>
                            <InputLabel for="tracking_code" value="Tracking Code *" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="tracking_code" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.tracking_code" 
                                placeholder="TRK-99201" 
                                required 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.tracking_code" />
                        </div>

                        <!-- Submitted By ID -->
                        <div>
                            <InputLabel for="received_by" value="Submitted By (User ID) *" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="received_by" 
                                type="number" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.received_by" 
                                placeholder="User ID #" 
                                required 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.received_by" />
                        </div>

                        <!-- Submitter CNIC -->
                        <div>
                            <InputLabel for="submitter_cnic" value="Submitter CNIC" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="submitter_cnic" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.submitter_cnic" 
                                placeholder="35201-7654321-9" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.submitter_cnic" />
                        </div>

                        <!-- Dealer ID -->
                        <div>
                            <InputLabel for="dealer_id" value="Dealer ID" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="dealer_id" 
                                type="number" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.dealer_id" 
                                placeholder="DLR-0042" 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_id" />
                        </div>

                        <!-- Dealer Phone -->
                        <div>
                            <InputLabel for="dealer_phone" value="Dealer Phone *" class="text-xs font-semibold text-slate-700 uppercase tracking-wider" />
                            <TextInput 
                                id="dealer_phone" 
                                type="text" 
                                class="mt-1.5 block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500/20" 
                                v-model="form.dealer_phone" 
                                placeholder="+92 300 0000000" 
                                required 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_phone" />
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <Link 
                        :href="route('invoices.index')"
                        class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400/20"
                    >
                        Cancel
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl shadow-sm hover:shadow transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ form.processing ? 'Saving...' : 'Save Invoice' }}</span>
                    </button>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>