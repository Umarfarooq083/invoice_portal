<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    dealer_phone: '',
    cnic: '',
    dealer_state: '',
    is_parse_data: false,
    Authorize: true,
    dealer_relation: '',
    dealer_father: '',
});

function formatCnic(e) {
    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,5})(\d{0,7})(\d{0,1})/);
    form.cnic = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
}

function submit() {
    form.post(route('dealers.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create Dealer" />

        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('dealers.index')"
                            class="btn-icon rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Create Dealer</h1>
                    </div>
                    <p class="mt-2 text-sm text-slate-500 ml-12">Register a new dealer profile.</p>
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
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-800">Dealer Information</h2>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <InputLabel for="name" value="Full Name *" class="label" />
                            <TextInput id="name" type="text" class="mt-1" v-model="form.name" required placeholder="John Doe" />
                            <InputError class="mt-1.5" :message="form.errors.name" />
                        </div>

                        <!-- CNIC -->
                        <div>
                            <InputLabel for="cnic" value="CNIC *" class="label" />
                            <TextInput id="cnic" type="text" class="mt-1 font-mono" v-model="form.cnic" @input="formatCnic" required placeholder="00000-0000000-0" />
                            <InputError class="mt-1.5" :message="form.errors.cnic" />
                        </div>

                        <!-- State -->
                        <div>
                            <InputLabel for="dealer_state" value="State / Region *" class="label" />
                            <TextInput id="dealer_state" type="text" class="mt-1" v-model="form.dealer_state" required placeholder="Punjab" />
                            <InputError class="mt-1.5" :message="form.errors.dealer_state" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <InputLabel for="dealer_phone" value="Phone Number" class="label" />
                            <TextInput id="dealer_phone" type="text" class="mt-1" v-model="form.dealer_phone" placeholder="0300-1234567" />
                            <InputError class="mt-1.5" :message="form.errors.dealer_phone" />
                        </div>

                        <!-- Relation -->
                        <div>
                            <InputLabel for="dealer_relation" value="Relation" class="label" />
                            <div class="relative mt-1">
                                <select id="dealer_relation" 
                                    class="input appearance-none pr-8 cursor-pointer w-full" 
                                    v-model="form.dealer_relation">
                                    <option value="" disabled selected>Select</option>
                                    <option value="S/O">S/O</option>
                                    <option value="D/O">D/O</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.dealer_relation" />
                        </div>

                        <!-- Father's Name -->
                        <div>
                            <InputLabel for="dealer_father" value="Father's/Spouse's Name" class="label" />
                            <TextInput id="dealer_father" type="text" class="mt-1" v-model="form.dealer_father" placeholder="Ali Raza" />
                            <InputError class="mt-1.5" :message="form.errors.dealer_father" />
                        </div>
                        
                        <!-- Authorize -->
                        <div class="md:col-span-2 pt-4">
                            <label class="flex items-center gap-3 cursor-pointer group w-max">
                                <input type="checkbox" 
                                    class="w-5 h-5 rounded border-slate-300 transition-all cursor-pointer" 
                                    style="color: var(--color-primary);"
                                    :checked="form.Authorize" 
                                    @change="form.Authorize = $event.target.checked ? 1 : 0">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">
                                        Authorize Dealer
                                    </span>
                                    <span class="text-xs text-slate-400">Allow this dealer to access the portal.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 pb-12 pr-8">
                        <Link :href="route('dealers.index')" class="btn-secondary btn-lg">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing" class="btn-primary btn-lg min-w-[160px]">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ form.processing ? 'Saving...' : 'Create Dealer' }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>