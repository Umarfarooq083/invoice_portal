<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    dropdowns: Object,
});

function generateTrackingCode() {
    return Math.random().toString(36).substring(2, 12).toUpperCase();
}

function generateBoxNo() {
    const now = new Date();
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = String(now.getFullYear()).slice(-2);
    return `${day}${month}${year}`;
}

const form = useForm({
    form_no: '',
    tracking_code: generateTrackingCode(),
    plot_price: '',
    down_payment: '',
    reg_type: '',
    address: '',
    society_id: '',
    size: '',
    client_name: '',
    contact: '',
    client_cnic: '',
    box_no: generateBoxNo(),
    sr_no: '',
    submitted_by: '',
    submitter_cnic: '',
    deposite_slip_no: '',
    dealer_id: '',
});

// API lookup state
const apiLoading = ref(false);
const apiError = ref('');
const apiSuccess = ref(false);
const liveFormNo = ref('');
const apiPlotSize = ref('');  // raw plot_size value returned by the API

// Show form_no lookup field only when block is selected
const showFormNoLookup = computed(() => !!form.society_id);

async function fetchFormData() {
    const num = liveFormNo.value.toString().trim();
    if (!num) return;

    apiLoading.value = true;
    apiError.value = '';
    apiSuccess.value = false;

    try {
        const response = await fetch(
            `${route('forms.booking-data')}?form_no=${encodeURIComponent(num)}`,
            {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            }
        );

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error ?? `Server error: ${response.status}`);
        }
        if (data && data.id) {
            form.form_no      = data.form_no       ?? form.form_no;
            form.plot_price   = data.plot_price    ?? form.plot_price;
            form.down_payment = data.down_payment  ?? form.down_payment;
            form.client_name  = data.client_name   ?? form.client_name;
            form.address      = data.client_address ?? form.address;
            form.client_cnic  = data.client_cnic   ?? form.client_cnic;
            form.contact      = data.contact       ?? form.contact;

            // Set App Size directly from the API value
            if (data.plot_size) {
                apiPlotSize.value = data.plot_size.toString().trim();
                form.size = apiPlotSize.value;
            }

            apiSuccess.value = true;
        } else {
            apiError.value = 'No record found. Please check the Form No.';
        }
    } catch (err) {
        apiError.value = 'Request failed: ' + err.message;
    } finally {
        apiLoading.value = false;
    }
}

function onFormNoKeydown(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        fetchFormData();
    }
}

function submit() {
    form.post(route('forms.store'), {
        preserveScroll: true,
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Create Form</h1>
                </div>
                <Link :href="route('forms.index')" class="btn btn-primary">
                    Back to List
                </Link>
            </div>
        </template>

        <Head title="Create Form" />

        <div class="card p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-1">
                        <InputLabel value="Block" />
                        <select
                            class="input"
                            v-model.number="form.society_id"
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
                        <InputError :message="form.errors.society_id" />
                    </div>

                    <!-- Form No Lookup - appears after block is selected -->
                    <div class="lg:col-span-1" v-if="showFormNoLookup">
                        <InputLabel value="Form No (Live Lookup)" />
                        <div class="form-no-lookup-wrapper" style="position: relative;">
                            <input
                                class="input"
                                type="number"
                                v-model="liveFormNo"
                                placeholder="Type a number and press Enter..."
                                @keydown="onFormNoKeydown"
                                :disabled="apiLoading"
                                style="padding-right: 2.5rem;"
                            />
                            <span
                                v-if="apiLoading"
                                style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);display:flex;align-items:center;"
                            >
                                <svg class="api-spinner" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                </svg>
                            </span>
                        </div>
                        <p v-if="apiError" style="color:#e53e3e;font-size:0.8rem;margin-top:0.25rem;">⚠ {{ apiError }}</p>
                        <p v-if="apiSuccess" style="color:#38a169;font-size:0.8rem;margin-top:0.25rem;">✓ Data loaded successfully.</p>
                        <p v-if="!apiSuccess" style="color:#718096;font-size:0.75rem;margin-top:0.2rem;">Press Enter to fetch.</p>
                    </div>

                     <div class="lg:col-span-1">
                        <InputLabel value="App Type" />
                        <select
                            class="input"
                            v-model.number="form.reg_type"
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
                        <InputError :message="form.errors.reg_type" />
                    </div>

                    <div class="lg:col-span-1">
                        <InputLabel value="Form No" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.form_no"
                            placeholder="Enter form no" readonly disabled
                        />
                        <InputError :message="form.errors.form_no" />
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
                        <InputLabel value="App Size" />
                        <select
                            class="input"
                            v-model="form.size"
                        >
                            <option value="">Select Plot Size</option>
                            <!-- After API fetch: show only the API plot_size value -->
                            <template v-if="apiPlotSize">
                                <option :value="apiPlotSize">{{ apiPlotSize }}</option>
                            </template>
                            <!-- Before API fetch: show full config list -->
                            <template v-else>
                                <option
                                    v-for="size in dropdowns.app_sizes"
                                    :key="size"
                                    :value="size"
                                >
                                    {{ size }}
                                </option>
                            </template>
                        </select>
                        <InputError :message="form.errors.size" />
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
                        <InputLabel value="Address" />
                        <input
                            class="input"
                            type="text"
                            v-model="form.address"
                            placeholder="Enter Address"
                        />
                        <InputError :message="form.errors.address" />
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
                    <SecondaryButton :href="route('forms.index')">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing">
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Save Form</span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
.api-spinner {
    animation: spin 0.8s linear infinite;
    color: #667eea;
}
</style>
