<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import axios from 'axios';

const showPreview = ref(false);
const isFetching = ref(false);
const apiError = ref('');
const apiSuccess = ref(false);

function formatCurrency(value) {
    if (!value) return '-';
    return new Intl.NumberFormat('en-PK', {
        maximumFractionDigits: 0,
    }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

const printPage = () => {
    window.print();
};

const props = defineProps({
    box_no: {
        type: String,
        required: true
    },
    next_sr_no: {
        type: Number,
        required: true
    },
    dealers: {
        type: Array,
        default: () => []
    },
    blocks: {
        type: Array,
        default: () => []
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
    society_id: '',
});

function formatCnic(e, field) {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
    form[field] = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
}

const submit = () => {
    form.post(route('invoices.store'));
};

const fetchFileData = async () => {
    if (!form.search_open_reg || !form.society_id) return;

    isFetching.value = true;
    apiError.value = '';
    apiSuccess.value = false;

    try {
        const response = await axios.get(route('invoices.fetch-file-data'), {
            params: {
                reg_no: form.search_open_reg,
                society_id: form.society_id
            }
        });

        if (response.data && response.data.success && response.data.data) {
            const data = response.data.data;
            if (data.reg_no) form.reg_no = data.reg_no;
            if (data.security_code) form.security_code = data.security_code;
            if (data.plot_size_title) form.size = data.plot_size_title;
            if (data.plot_type_title) form.plot_type = data.plot_type_title;
            if (data.payment_plan_title) form.downpayment = data.payment_plan_title;
            if (data.payment_plan_plot_price_gen ) form.plot_price = data.payment_plan_plot_price_gen;
            apiSuccess.value = true;
        } else {
            apiError.value = response.data?.message || 'Data not found. Please check the Reg No.';
        }
    } catch (error) {
        console.error('Error fetching file data:', error);
        apiError.value = 'Failed to fetch data from the API.';
    } finally {
        isFetching.value = false;
    }
};

function onSearchKeydown(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchFileData();
    }
}
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Create Invoice" />
        <Modal :show="showPreview" @close="showPreview = false" maxWidth="4xl">
            <div class="receipt-container" id="print-section">
                <div class="top-bar hide-on-print">
                    <button @click="showPreview = false" class="back-btn">&#8592; Back to Form</button>
                    <!-- <button @click="printPage" class="print-btn">Print</button> -->
                </div>

                <div class="receipt">
                    <div class="receipt-title">
                        <h1>Invoice / Receipt</h1>
                        <div class="copy-label">(Customer Copy)</div>
                    </div>

                    <div class="section-header">
                        <div>Box No: {{ form.box_no ?? '-' }}</div>
                        <div class="center">Invoice Details</div>
                        <div>Sr No: {{ form.sr_no ?? '-' }}</div>
                    </div>

                    <table class="details-table">
                        <tr>
                            <td class="label" style="width:15%;">Reg No:</td>
                            <td style="width:30%;">{{ form.reg_no ?? '-' }}</td>
                            <td class="label col-divider" style="width:15%;">Name:</td>
                            <td>{{ form.client_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Plot Size:</td>
                            <td>{{ form.size ?? '-' }}</td>
                            <td class="label col-divider">CNIC:</td>
                            <td>{{ form.client_cnic ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Plot Category:</td>
                            <td>{{ form.plot_type ?? '-' }}</td>
                            <td class="label col-divider">Contact:</td>
                            <td>{{ form.contact ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Down Payment:</td>
                            <td>{{ formatCurrency(form.downpayment) }}</td>
                            <td class="label col-divider">Address:</td>
                            <td>{{ form.address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Plot Price:</td>
                            <td>{{ formatCurrency(form.plot_price) }}</td>
                            <td class="col-divider"></td>
                            <td></td>
                        </tr>
                    </table>

                    <div class="disclaimer">
                        This Receipt is subject to confirmation of Payment clearance from Bank/Accounts Dept.
                    </div>

                    <div class="submit-row">
                        <div><span>Dealer ID:</span><span class="underline-blank">{{ form.dealer_id ?? '' }}</span>
                        </div>
                        <div><span>Dealer Phone:</span><span class="underline-blank">{{ form.dealer_phone ?? ''
                                }}</span></div>
                        <div><span>Tracking Code:</span><span class="underline-blank">{{ form.tracking_code ?? ''
                                }}</span></div>
                    </div>

                    <div class="officer-box">
                        <div class="officer-row">
                            <div><span class="label">Submitted By (User ID):</span> <u>{{ form.received_by ?? '-' }}</u>
                            </div>
                            <div><span class="label">Submitter CNIC:</span> <u>{{ form.submitter_cnic ?? '-' }}</u>
                            </div>
                            <div><span class="label">Date:</span> <u>{{ formatDate(new Date()) }}</u></div>
                        </div>
                    </div>
                </div>

                <hr class="footer-line">
            </div>
        </Modal>

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
                        <!-- Block (Society ID) -->
                        <div>
                            <InputLabel for="society_id" value="Block" class="label" />
                            <select id="society_id"
                                class="input mt-1"
                                v-model="form.society_id">
                                <option value="" disabled>Select a Block</option>
                                <option v-for="block in blocks" :key="block.id" :value="block.id">
                                    {{ block.name }}
                                </option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.society_id" />
                        </div>

                        <template v-if="form.society_id">
                            <div class="col-span-full lg:col-span-1">
                                <InputLabel for="search_open_reg" value="Search Open Reg (Live Lookup)" class="label" />
                                <div class="relative mt-1" style="position: relative;">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <TextInput id="search_open_reg" type="text" class="pl-10 mt-0"
                                        style="padding-right: 2.5rem;" v-model="form.search_open_reg"
                                        @keydown="onSearchKeydown" :disabled="isFetching"
                                        placeholder="Type and press Enter..." />
                                    <span v-if="isFetching"
                                        style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);display:flex;align-items:center;">
                                        <svg class="animate-spin h-5 w-5 text-indigo-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                        </svg>
                                    </span>
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.search_open_reg" />
                                <p v-if="apiError" style="color:#e53e3e;font-size:0.8rem;margin-top:0.25rem;">⚠ {{
                                    apiError }}</p>
                                <p v-if="apiSuccess" style="color:#38a169;font-size:0.8rem;margin-top:0.25rem;">✓ Data
                                    loaded successfully.</p>
                                <p v-if="!apiSuccess && !apiError"
                                    style="color:#718096;font-size:0.75rem;margin-top:0.2rem;">Press Enter to fetch.</p>
                            </div>
                            <!-- Reg No -->
                            <div>
                                <InputLabel for="reg_no" value="Reg Number" class="label" />
                                <TextInput id="reg_no" type="text" class="mt-1 bg-slate-50" v-model="form.reg_no"
                                    readonly disabled placeholder="REG-10492" />
                                <InputError class="mt-1.5" :message="form.errors.reg_no" />
                            </div>
                            <!-- Security Code -->
                            <div>
                                <InputLabel for="security_code" value="Security Code" class="label" />
                                <TextInput id="security_code" type="text" class="mt-1 bg-slate-50" v-model="form.security_code"
                                    readonly disabled placeholder="SEC-XXXX" />
                                <InputError class="mt-1.5" :message="form.errors.security_code" />
                            </div>
                            <!-- Size -->
                            <div>
                                <InputLabel for="size" value="Plot Size" class="label" />
                                <TextInput id="size" type="text" class="mt-1 bg-slate-50" v-model="form.size"
                                    readonly disabled placeholder="5 Marla, 10 Marla" />
                                <InputError class="mt-1.5" :message="form.errors.size" />
                            </div>
                            <!-- Plot Type -->
                            <div>
                                <InputLabel for="plot_type" value="Plot Type" class="label" />
                                <TextInput id="plot_type" type="text" class="mt-1 bg-slate-50" v-model="form.plot_type"
                                    readonly disabled placeholder="Residential / Commercial" />
                                <InputError class="mt-1.5" :message="form.errors.plot_type" />
                            </div>

                            <!-- Downpayment -->
                            <div>
                                <InputLabel for="downpayment" value="Downpayment" class="label" />
                                <div class="relative mt-1">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                    <TextInput id="downpayment" type="text" class="pl-12 mt-0 bg-slate-50"
                                        v-model="form.downpayment" readonly disabled placeholder="0.00" />
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.downpayment" />
                            </div>
                            <!-- Total Plot Price -->
                            <div>
                                <InputLabel for="plot_price" value="Plot Price" class="label" />
                                <div class="relative mt-1">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                    <TextInput id="plot_price" type="number" step="0.01"
                                        class="pl-12 mt-0 font-semibold text-slate-900 bg-slate-50" v-model="form.plot_price"
                                        readonly disabled placeholder="0.00" />
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.plot_price" />
                            </div>
                            <!-- BOX NO -->
                            <div>
                                <InputLabel for="box_no" value="BOX NO" class="label" />
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
                                <InputLabel for="sr_no" value="SR NO" class="label" />
                                <div class="relative mt-1">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-semibold">PKR</span>
                                    <TextInput id="sr_no" type="number" step="0.01"
                                        class="pl-12 mt-0 font-semibold text-slate-900" v-model="form.sr_no" readonly
                                        disabled placeholder="0.00" />
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.sr_no" />
                            </div>
                        </template>
                    </div>

                    <template v-if="form.society_id">
                        <div
                            class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
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
                                <TextInput id="client_cnic" type="text" class="mt-1 font-mono"
                                    v-model="form.client_cnic" @input="formatCnic($event, 'client_cnic')"
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

                        <div
                            class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
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
                                <TextInput id="submitter_cnic" type="text" class="mt-1 font-mono"
                                    v-model="form.submitter_cnic" @input="formatCnic($event, 'submitter_cnic')"
                                    placeholder="35201-7654321-9" />
                                <InputError class="mt-1.5" :message="form.errors.submitter_cnic" />
                            </div>
                            <!-- Dealer ID -->
                            <div>
                                <InputLabel for="dealer_id" value="Dealer ID" class="label" />
                                <select id="dealer_id" class="input mt-1" v-model="form.dealer_id">
                                    <option value="" disabled>Select a Dealer</option>
                                    <option v-for="dealer in dealers" :key="dealer.id" :value="dealer.id">
                                        {{ dealer.name }} ({{ dealer.id }})
                                    </option>
                                </select>
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
                            <button type="button" @click="showPreview = true"
                                class="btn-secondary btn-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border-indigo-200">
                                Preview
                            </button>

                            <button type="submit" :disabled="form.processing" class="btn-primary btn-lg min-w-[160px]">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>{{ form.processing ? 'Saving...' : 'Save Invoice' }}</span>
                            </button>
                        </div>
                    </template>
                </div>
                <!-- Form Action Buttons -->
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    body * {
        visibility: hidden;
    }

    #print-section,
    #print-section * {
        visibility: visible;
    }

    #print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        max-height: none !important;
        overflow: visible !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
}
</style>
<style scoped>
.receipt-container {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    color: #000;
    background: #fff;
    padding: 40px;
    width: 100%;
    max-width: 750px;
    margin: 0 auto;
    box-sizing: border-box;
    max-height: 85vh;
    overflow-y: auto;
    min-height: 890px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    border-radius: 8px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #000;
    padding-bottom: 8px;
    margin-bottom: 10px;
}

.top-bar .back-btn,
.print-btn {
    color: #1a56db;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.print-btn:hover,
.top-bar .back-btn:hover {
    text-decoration: underline;
}

.receipt {
    margin-bottom: 25px;
}

.receipt-title {
    text-align: center;
    position: relative;
    margin: 10px 0 4px 0;
}

.receipt-title h1 {
    font-size: 22px;
    font-weight: normal;
    margin: 0;
    display: inline-block;
}

.copy-label {
    position: relative;
    float: right;
    top: -28px;
    font-weight: bold;
    font-size: 13px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #000;
    padding-bottom: 4px;
    margin-bottom: 4px;
    font-weight: normal;
}

.section-header .center {
    flex: 1;
    text-align: center;
}

.details-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
}

.details-table td {
    border: none;
    padding: 4px 8px;
    vertical-align: top;
    font-size: 13px;
}

.details-table tr {
    border-bottom: 1px dotted #000;
}

.details-table tr:last-child {
    border-bottom: none;
}

.label {
    font-weight: bold;
    white-space: nowrap;
}

.col-divider {
    border-left: 1px solid #000;
}

.disclaimer {
    font-size: 12px;
    margin: 6px 0 14px 0;
}

.submit-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
    font-size: 13px;
}

.submit-row span {
    font-weight: bold;
}

.underline-blank {
    border-bottom: 1px solid #000;
    display: inline-block;
    min-width: 140px;
    margin-left: 4px;
    text-align: center;
    font-weight: normal !important;
}

.officer-box {
    border: 1px solid #000;
    padding: 12px;
}

.officer-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
    font-size: 13px;
}

.officer-row:last-child {
    margin-bottom: 4px;
}

.officer-row .label {
    font-weight: bold;
}

.officer-row u {
    font-weight: normal;
    text-decoration: underline;
}

hr.footer-line {
    border: none;
    border-top: 1px solid #000;
    margin-top: 30px;
}

@media print {
    .hide-on-print {
        display: none !important;
    }

    body {
        background: #fff;
    }

    .receipt-container {
        padding: 0;
        margin: 0;
        min-height: auto;
    }
}
</style>