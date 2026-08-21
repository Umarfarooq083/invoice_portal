<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

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
    payment_plan_plot_price: '',
    payment_plan_live_id: '',
    payment_plan_down_payment: '',
    ledger_down_payment: '',
    ledger_plot_price: '',
    sum_payment: '',
    received_downpayment: '',

    // --- Merge To ---
    merge_to_no: '',
    to_security_code: '',
    to_size: '',
    merge_app_type: '',
    ledger_amount: '',
    merging_fee: '',
    to_payment_plan_plot_price: '',
    to_payment_plan_live_id: '',
    to_payment_plan_down_payment: '',
    box_no: '',
    tracking_code: '',
    dealer_name: '',
    dealer_phone: '',
    submitter_cnic: '',
});

const isFetching = ref(false);
const isFetchingMergeTo = ref(false);

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

const fetchFromAppData = () => {
    if (!form.from_app_no) return;
    isFetching.value = true;
    // Simulate API call for "From App No"
    setTimeout(() => {
        isFetching.value = false;
        console.log("Data fetched for From App No: ", form.from_app_no);
    }, 1000);
};

const fetchMergeToData = () => {
    if (!form.merge_to_no) return;
    isFetchingMergeTo.value = true;
    // Simulate API call for "Merge To No"
    setTimeout(() => {
        isFetchingMergeTo.value = false;
        console.log("Data fetched for Merge To No: ", form.merge_to_no);
    }, 1000);
};

const onSearchFromKeydown = (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchFromAppData();
    }
};

const onSearchToKeydown = (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchMergeToData();
    }
};

const formatCnic = (e, field) => {
    var x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
    form[field] = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
};

const submit = () => {
    console.log('Form Submitted', form.data());
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

                <!-- Section 1: Block Selection -->
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

                        <!-- Main Block Selection -->
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

                        <!-- Shown for Awami Greens (1) AND NAC/Downtown (1st of 2) -->
                        <div v-if="showOneExtraDropdown || showTwoExtraDropdowns">
                            <InputLabel for="sub_option_1" value="Select File Type" class="label" />
                            <select class="input mt-1" v-model="form.sub_option_1">
                                <option value="" disabled>Select File Type</option>
                                <option value="1">By-Name</option>
                                <option value="2">Open</option>
                                <option value="3">Form</option>
                            </select>
                            <InputError class="mt-1.5" :message="form.errors.sub_option_1" />
                        </div>

                        <!-- Shown ONLY for NAC/Downtown (2nd of 2) -->
                        <div v-if="showTwoExtraDropdowns">
                            <InputLabel for="sub_option_2" value="Select Merging Type" class="label" />
                            <select id="sub_option_2" class="input mt-1" v-model="form.sub_option_2">
                                <option value="" disabled>Select option...</option>
                                <option value="optA">Option A</option>
                                <option value="optB">Option B</option>
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
                                        placeholder="Type and press Enter..." required />
                                    <span v-if="isFetching"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center">
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
                                    v-model="form.ledger_down_payment" />
                            </div>

                            <div>
                                <InputLabel for="ledger_plot_price" value="Ledger Plot Price" class="label" />
                                <TextInput id="ledger_plot_price" type="number" step="0.01" class="mt-1"
                                    v-model="form.ledger_plot_price" />
                            </div>

                            <div>
                                <InputLabel for="sum_payment" value="Sum Payment" class="label" />
                                <TextInput id="sum_payment" type="number" step="0.01" class="mt-1"
                                    v-model="form.sum_payment" />
                            </div>

                            <div>
                                <InputLabel for="received_downpayment" value="Received Downpayment" class="label" />
                                <TextInput id="received_downpayment" type="number" step="0.01" class="mt-1"
                                    v-model="form.received_downpayment" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Merge To Details -->
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
                                <h2 class="text-base font-semibold text-slate-800">Merge To Details</h2>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            <!-- Merge To No with Verify -->
                            <div class="col-span-full lg:col-span-1">
                                <InputLabel for="merge_to_no" value="Merge To No *" class="label" />
                                <div class="relative mt-1">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <TextInput id="merge_to_no" type="text" class="pl-10 mt-0"
                                        style="padding-right: 2.5rem;" v-model="form.merge_to_no"
                                        @keydown="onSearchToKeydown" :disabled="isFetchingMergeTo"
                                        placeholder="Type and press Enter..." required />
                                    <span v-if="isFetchingMergeTo"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center">
                                        <svg class="animate-spin h-5 w-5 text-indigo-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                        </svg>
                                    </span>
                                </div>
                                <InputError class="mt-1.5" :message="form.errors.merge_to_no" />
                            </div>

                            <div>
                                <InputLabel for="to_security_code" value="Security Code" class="label" />
                                <TextInput id="to_security_code" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.to_security_code" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="to_size" value="Size" class="label" />
                                <TextInput id="to_size" type="text" class="mt-1 bg-slate-50" v-model="form.to_size"
                                    readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="merge_app_type" value="Merge App Type" class="label" />
                                <TextInput id="merge_app_type" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.merge_app_type" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="ledger_amount" value="Ledger Amount" class="label" />
                                <TextInput id="ledger_amount" type="number" step="0.01" class="mt-1"
                                    v-model="form.ledger_amount" />
                            </div>

                            <div>
                                <InputLabel for="merging_fee" value="Merging Fee" class="label" />
                                <TextInput id="merging_fee" type="number" step="0.01" class="mt-1"
                                    v-model="form.merging_fee" />
                            </div>

                            <div>
                                <InputLabel for="to_payment_plan_plot_price" value="Payment Plan Plot Price"
                                    class="label" />
                                <TextInput id="to_payment_plan_plot_price" type="number" step="0.01"
                                    class="mt-1 bg-slate-50" v-model="form.to_payment_plan_plot_price" readonly
                                    disabled />
                            </div>

                            <div>
                                <InputLabel for="to_payment_plan_live_id" value="Payment Plan Live ID" class="label" />
                                <TextInput id="to_payment_plan_live_id" type="text" class="mt-1 bg-slate-50"
                                    v-model="form.to_payment_plan_live_id" readonly disabled />
                            </div>

                            <div>
                                <InputLabel for="to_payment_plan_down_payment" value="Payment Plan Down Payment"
                                    class="label" />
                                <TextInput id="to_payment_plan_down_payment" type="number" step="0.01"
                                    class="mt-1 bg-slate-50" v-model="form.to_payment_plan_down_payment" readonly
                                    disabled />
                            </div>

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
