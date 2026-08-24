<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import axios from 'axios';

const props = defineProps({
    blocks: Array
});

const form = useForm({
    society_id: '',
    sub_option_1: '',
    sub_option_2: '',

    // --- App (From) ---
    registration_no: '',
    from_app_no: '',
    from_security_code: '',
    from_size: '',
    client_name: '',
    client_cnic: '',
    app_type: '',
    payment_plan_plot_price: 0,
    payment_plan_live_id: '',
    payment_plan_down_payment: 0,
    ledger_down_payment: 0,
    ledger_plot_price: 0,
    sum_payment: 0,
    received_downpayment: 0,

    // --- Merge To ---
    merge_to_details: [
        {
            merge_to: '',
            merge_to_no: '',
            to_security_code: '',
            to_size: '',
            merge_app_type: '',
            ledger_amount: '',
            merging_fee: '',
            to_payment_plan_plot_price: '',
            to_payment_plan_live_id: '',
            to_payment_plan_down_payment: '',
        }
    ],

    // --- Client Details ---
    box_no: '',
    tracking_code: '',
    dealer_name: '',
    dealer_phone: '',
    submitter_cnic: '',
});

const isFetching = ref(false);
const isFetchingMergeTo = ref({});

// Computed properties to handle visibility based on block selection
const selectedBlockName = computed(() => {
    if (!form.society_id) return '';
    const block = props.blocks.find(b => b.id === form.society_id);
    return block ? block.name : '';
});

const showOneExtraDropdown = computed(() => {
    return selectedBlockName.value === 'Awami Greens';
});

const showTwoExtraDropdowns = computed(() => {
    return selectedBlockName.value === 'Blue World NAC-06' || selectedBlockName.value === 'Down Town';
});

const fileTypeOptions = computed(() => {
    if (selectedBlockName.value === 'Awami Greens') {
        return [
            { value: '1', label: 'By-name' },
            { value: '2', label: 'Open' },
            { value: '3', label: 'Form' }
        ];
    } else if (selectedBlockName.value === 'Blue World NAC-06' || selectedBlockName.value === 'Down Town') {
        return [
            { value: '2', label: 'Open' }
        ];
    }
    return [];
});

const mergingTypeOptions = computed(() => {
    if (selectedBlockName.value === 'Blue World NAC-06') {
        return [
            { value: '1', label: 'Commercial' }
        ];
    } else if (selectedBlockName.value === 'Down Town') {
        return [
            { value: '1', label: 'Open Awami Greens' },
            { value: '2', label: 'Sector A' },
            { value: '3', label: 'DT Open Form Merging' }
        ];
    }
    return [];
});

watch(() => form.society_id, () => {
    form.sub_option_1 = '';
    form.sub_option_2 = '';
});

const fetchFromAppData = () => {
    if (!form.registration_no || !form.society_id) {
        alert("Please select Block and enter App No");
        return;
    }
    isFetching.value = true;

    axios.get(route('mergers.fetch-main-app-data'), {
        params: {
            reg_no: form.registration_no,
            society_id: form.society_id,
            is_open: form.sub_option_1,
        }
    })
        .then(response => {
            isFetching.value = false;
            console.log("Data fetched for main App No: ", response.data);

            const data = response.data.data || response.data;
            if (data && (response.data.success !== false)) {
                if (data.reg_no) form.from_app_no = data.reg_no;
                if (data.security_code) form.from_security_code = data.security_code;
                if (data.plot_size_title) form.from_size = data.plot_size_title;
                if (data.member_name) form.client_name = data.member_name;
                if (data.client_cnic) form.client_cnic = data.client_cnic;
                if (data.plot_type_title) form.app_type = data.plot_type_title;
                if (data.payment_plan_plot_price) form.payment_plan_plot_price = data.payment_plan_plot_price;
                if (data.payment_plan_id) form.payment_plan_live_id = data.payment_plan_id;
                if (data.payment_plan_down_payment) form.payment_plan_down_payment = data.payment_plan_down_payment;
                if (data.legder_down_payment) form.ledger_down_payment = data.legder_down_payment;
                if (data.legder_plot_price) form.ledger_plot_price = data.legder_plot_price;
                if (data.sum_payment) form.sum_payment = data.sum_payment;
                if (data.received_downpayment) form.received_downpayment = data.received_downpayment;
            } else {
                alert(response.data?.message || 'Data not found. Please check the Reg No.');
            }
        })
        .catch(error => {
            isFetching.value = false;
            console.error("Error fetching data:", error);
            alert("Failed to fetch data.");
        });
};

const fetchMergeToData = (index) => {
    const detail = form.merge_to_details[index];
    if (!detail.merge_to) return;
    isFetchingMergeTo.value[index] = true;
    // Simulate API call for "Merge To"
    setTimeout(() => {
        isFetchingMergeTo.value[index] = false;
        console.log("Data fetched for Merge To: ", detail.merge_to, " at index ", index);
    }, 1000);
};

const onSearchFromKeydown = (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchFromAppData();
    }
};

const onSearchToKeydown = (e, index) => {
    // alert(form.from_app_no);
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchMergeToData(index);
    }
};

const addMergeToDetail = () => {
    form.merge_to_details.push({
        merge_to: '',
        merge_to_no: '',
        to_security_code: '',
        to_size: '',
        merge_app_type: '',
        ledger_amount: '',
        merging_fee: '',
        to_payment_plan_plot_price: '',
        to_payment_plan_live_id: '',
        to_payment_plan_down_payment: '',
    });
};

const removeMergeToDetail = (index) => {
    if (form.merge_to_details.length > 1) {
        form.merge_to_details.splice(index, 1);
    }
};

const formatCnic = (e, field) => {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
    form[field] = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
};

const submit = () => {
    console.table('Form Submitted', form.data());
    console.table('Form Submitted', form.data().merge_to_details);
};

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Create Merger" />
        <div class="max-w-9xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('mergers.index')"
                            class="btn-icon rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Create Merger</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8 animate-slide-up stagger-1">

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
                            <h2 class="text-base font-semibold text-slate-800">Block & Type Selection</h2>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <div>
                            <InputLabel for="block" value="Select Block" class="label" />
                            <select id="block" class="input mt-1" v-model="form.society_id" required>
                                <option value="" disabled>Select a block...</option>
                                <option v-for="block in blocks" :key="block.id" :value="block.id">
                                    {{ block.name }}
                                </option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.society_id" />
                        </div>

                        <div v-if="showOneExtraDropdown || showTwoExtraDropdowns">
                            <InputLabel for="sub_option_1" value="Select File Type" class="label" />
                            <select class="input mt-1" v-model="form.sub_option_1">
                                <option value="" disabled>Select File Type</option>
                                <option v-for="option in fileTypeOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.sub_option_1" />
                        </div>

                        <div v-if="showTwoExtraDropdowns">
                            <InputLabel for="sub_option_2" value="Select Merging Type" class="label" />
                            <select id="sub_option_2" class="input mt-1" v-model="form.sub_option_2">
                                <option value="" disabled>Select option...</option>
                                <option v-for="option in mergingTypeOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.sub_option_2" />
                        </div>
                    </div>
                </div>

                <template v-if="form.society_id">
                    <!-- Section 2: App Details (From) -->
                    <div class="card overflow-hidden">
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
                                <h2 class="text-base font-semibold text-slate-800">App Details</h2>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="col-span-full lg:col-span-1">
                                <InputLabel for="registration_no" value="App *" class="label" />
                                <div class="relative mt-1">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <TextInput id="registration_no" type="text" class="pl-10 mt-0"
                                        style="padding-right: 2.5rem;" v-model="form.registration_no"
                                        @keydown="onSearchFromKeydown" :disabled="isFetching"
                                        placeholder="Type and press Enter or Click Search..." required />
                                    <button v-if="!isFetching" type="button" @click="fetchFromAppData"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center text-slate-400 hover:text-indigo-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                    <span v-else class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center">
                                        <svg class="animate-spin h-5 w-5 text-indigo-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                        </svg>
                                    </span>
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.registration_no" />
                            </div>

                            <!-- From App No with Verify -->
                            <div class="col-span-full lg:col-span-1">
                                <InputLabel for="from_app_no" value="From App No *" class="label" />
                                <div class="relative mt-1">
                                    <TextInput id="from_app_no" type="text" class="mt-0" v-model="form.from_app_no"
                                        placeholder="Form No" required />
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.from_app_no" />
                            </div>

                            <div>
                                <InputLabel for="from_security_code" value="From Security Code" class="label" />
                                <TextInput id="from_security_code" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.from_security_code" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="from_size" value="From Size" class="label" />
                                <TextInput id="from_size" type="text" class="mt-1 bg-slate-50" v-model="form.from_size"
                                    readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="client_name" value="Client Name" class="label" />
                                <TextInput id="client_name" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.client_name" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="client_cnic" value="Client CNIC" class="label" />
                                <TextInput id="client_cnic" type="text" class="mt-1 font-mono bg-slate-50"
                                    v-model="form.client_cnic" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="app_type" value="App Type" class="label" />
                                <TextInput id="app_type" type="text" class="mt-1 bg-slate-50" v-model="form.app_type"
                                    readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="payment_plan_plot_price" value="Payment Plan Plot Price"
                                    class="label" />
                                <TextInput id="payment_plan_plot_price" type="number" step="0.01"
                                    class="mt-1 bg-slate-50" v-model="form.payment_plan_plot_price" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="payment_plan_live_id" value="Payment Plan Live ID" class="label" />
                                <TextInput id="payment_plan_live_id" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.payment_plan_live_id" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="payment_plan_down_payment" value="Payment Plan Down Payment"
                                    class="label" />
                                <TextInput id="payment_plan_down_payment" type="number" step="0.01"
                                    class="mt-1 bg-slate-50" v-model="form.payment_plan_down_payment" readonly
                                    disabled />
                            </div>

                            <div>
                                <InputLabel for="ledger_down_payment" value="Ledger Down Payment" class="label" />
                                <TextInput id="ledger_down_payment" type="number" step="0.01" class="mt-1"
                                    v-model="form.ledger_down_payment" disabled />
                            </div>

                            <div>
                                <InputLabel for="ledger_plot_price" value="Ledger Plot Price" class="label" />
                                <TextInput id="ledger_plot_price" type="number" step="0.01" class="mt-1"
                                    v-model="form.ledger_plot_price" disabled />
                            </div>

                            <div>
                                <InputLabel for="sum_payment" value="Sum Payment" class="label" />
                                <TextInput id="sum_payment" type="number" step="0.01" class="mt-1"
                                    v-model="form.sum_payment" disabled />
                            </div>

                            <div>
                                <InputLabel for="received_downpayment" value="Received Downpayment" class="label" />
                                <TextInput id="received_downpayment" type="number" step="0.01" class="mt-1"
                                    v-model="form.received_downpayment" disabled />
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Merge To Details -->
                    <div class="card overflow-hidden mb-8">
                        <div
                            class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white"
                                    style="background: var(--color-primary);">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <h2 class="text-base font-semibold text-slate-800">Merge To Details</h2>
                            </div>
                        </div>

                        <div class="p-6 space-y-6 bg-slate-50/30">
                            <div v-for="(detail, index) in form.merge_to_details" :key="index"
                                class="relative rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">

                                <!-- Inner Header for each detail -->
                                <div class="absolute top-4 right-4 flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-md"
                                        style="color: #2b33f8;">Detail #{{ index + 1 }}</span>
                                    <button v-if="form.merge_to_details.length > 1" type="button"
                                        @click="removeMergeToDetail(index)"
                                        class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-md transition"
                                        style="color:red" title="Remove Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
                                    <!-- Merge To No with Verify -->
                                    <div class="col-span-full lg:col-span-1">
                                        <InputLabel :for="'merge_to_' + index" value="Merge To *" class="label" />
                                        <div class="relative mt-1">
                                            <span
                                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </span>
                                            <TextInput :id="'merge_to_' + index" type="text" class="pl-10 mt-0"
                                                style="padding-right: 2.5rem;" v-model="detail.merge_to"
                                                @keydown="onSearchToKeydown($event, index)"
                                                :disabled="isFetchingMergeTo[index]"
                                                placeholder="Type and press Enter..." required />
                                            <span v-if="isFetchingMergeTo[index]"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center">
                                                <svg class="animate-spin h-5 w-5 text-indigo-500"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                                </svg>
                                            </span>
                                        </div>
                                        <InputError class="mt-1.5"
                                            :message="form.errors[`merge_to_details.${index}.merge_to`]" />
                                    </div>

                                    <div class="col-span-full lg:col-span-1">
                                        <InputLabel :for="'merge_to_no_' + index" value="Merge To No *" class="label" />
                                        <div class="relative mt-1">
                                            <TextInput :id="'merge_to_no_' + index" type="text" class="pl-10 mt-0"
                                                style="padding-right: 2.5rem;" v-model="detail.merge_to_no" required />
                                        </div>
                                        <InputError class="mt-1.5"
                                            :message="form.errors[`merge_to_details.${index}.merge_to_no`]" />
                                    </div>

                                    <div>
                                        <InputLabel :for="'to_security_code_' + index" value="Security Code"
                                            class="label" />
                                        <TextInput :id="'to_security_code_' + index" type="text"
                                            class="mt-1 bg-slate-50" v-model="detail.to_security_code" readonly
                                            disabled />
                                    </div>

                                    <div>
                                        <InputLabel :for="'to_size_' + index" value="Size" class="label" />
                                        <TextInput :id="'to_size_' + index" type="text" class="mt-1 bg-slate-50"
                                            v-model="detail.to_size" readonly disabled />
                                    </div>

                                    <div>
                                        <InputLabel :for="'merge_app_type_' + index" value="Merge App Type"
                                            class="label" />
                                        <TextInput :id="'merge_app_type_' + index" type="text" class="mt-1 bg-slate-50"
                                            v-model="detail.merge_app_type" readonly disabled />
                                    </div>

                                    <div>
                                        <InputLabel :for="'ledger_amount_' + index" value="Ledger Amount"
                                            class="label" />
                                        <TextInput :id="'ledger_amount_' + index" type="number" step="0.01" class="mt-1"
                                            v-model="detail.ledger_amount" />
                                    </div>

                                    <div>
                                        <InputLabel :for="'merging_fee_' + index" value="Merging Fee" class="label" />
                                        <TextInput :id="'merging_fee_' + index" type="number" step="0.01" class="mt-1"
                                            v-model="detail.merging_fee" />
                                    </div>

                                    <div>
                                        <InputLabel :for="'to_payment_plan_plot_price_' + index"
                                            value="Payment Plan Plot Price" class="label" />
                                        <TextInput :id="'to_payment_plan_plot_price_' + index" type="number" step="0.01"
                                            class="mt-1 bg-slate-50" v-model="detail.to_payment_plan_plot_price"
                                            readonly disabled />
                                    </div>

                                    <div>
                                        <InputLabel :for="'to_payment_plan_live_id_' + index"
                                            value="Payment Plan Live ID" class="label" />
                                        <TextInput :id="'to_payment_plan_live_id_' + index" type="text"
                                            class="mt-1 bg-slate-50" v-model="detail.to_payment_plan_live_id" readonly
                                            disabled />
                                    </div>

                                    <div>
                                        <InputLabel :for="'to_payment_plan_down_payment_' + index"
                                            value="Payment Plan Down Payment" class="label" />
                                        <TextInput :id="'to_payment_plan_down_payment_' + index" type="number"
                                            step="0.01" class="mt-1 bg-slate-50"
                                            v-model="detail.to_payment_plan_down_payment" readonly disabled />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Button at the bottom of the main card -->
                        <div class="flex justify-end p-4 border-t border-slate-100 bg-slate-50/50">
                            <button type="button" @click="addMergeToDetail"
                                style="background-color: #378737; color: #fff;"
                                class="btn-secondary flex items-center gap-2 bg-white hover:bg-slate-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <!-- Add Another Detail -->
                            </button>
                        </div>
                    </div>

                    <!-- Section 3: Client Details -->
                    <div class="card overflow-hidden">
                        <div
                            class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white"
                                    style="background: var(--color-primary);">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <h2 class="text-base font-semibold text-slate-800">Client Details</h2>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            <div>
                                <InputLabel for="box_no" value="Box No" class="label" />
                                <TextInput id="box_no" type="text" class="mt-1" v-model="form.box_no" />
                            </div>

                            <div>
                                <InputLabel for="tracking_code" value="Tracking Code" class="label" />
                                <TextInput id="tracking_code" type="text" class="mt-1" v-model="form.tracking_code" />
                            </div>

                            <div>
                                <InputLabel for="dealer_name" value="Dealer Name" class="label" />
                                <TextInput id="dealer_name" type="text" class="mt-1" v-model="form.dealer_name" />
                            </div>

                            <div>
                                <InputLabel for="dealer_phone" value="Dealer Phone" class="label" />
                                <TextInput id="dealer_phone" type="text" class="mt-1" v-model="form.dealer_phone" />
                            </div>

                            <div>
                                <InputLabel for="submitter_cnic" value="Submitter CNIC" class="label" />
                                <TextInput id="submitter_cnic" type="text" class="mt-1 font-mono"
                                    v-model="form.submitter_cnic" @input="formatCnic($event, 'submitter_cnic')"
                                    placeholder="00000-0000000-0" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 pb-12 mr-7">
                            <Link :href="route('mergers.index')" class="btn-secondary btn-lg">
                                Cancel
                            </Link>

                            <button type="submit" :disabled="form.processing" class="btn-primary btn-lg min-w-[160px]">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>{{ form.processing ? 'Saving...' : 'Save Merger' }}</span>
                            </button>
                        </div>
                    </div>

                </template>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
