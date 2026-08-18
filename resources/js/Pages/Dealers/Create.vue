<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

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

        <div class="max-w-7xl mx-auto py-6 px-4">
            <!-- Compact Header -->
            <div class="flex items-center gap-3 mb-6">
                <Link :href="route('dealers.index')"
                    class="flex items-center justify-center h-9 w-9 rounded-xl bg-white shadow-sm border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">New Dealer</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Register a new dealer profile</p>
                </div>
            </div>

            <!-- Compact Form Card -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-200/60 overflow-hidden">
                <div class="h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                
                <form @submit.prevent="submit" class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Name -->
                        <div>
                            <InputLabel for="name" class="text-xs font-semibold text-slate-600 mb-1.5">
                                Full Name <span class="text-red-400">*</span>
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" type="text" 
                                    class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none placeholder:text-slate-300" 
                                    v-model="form.name" required placeholder="John Doe" />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.name" />
                        </div>

                        <!-- CNIC -->
                        <div>
                            <InputLabel for="cnic" class="text-xs font-semibold text-slate-600 mb-1.5">
                                CNIC <span class="text-red-400">*</span>
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input id="cnic" type="text" 
                                    class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none placeholder:text-slate-300 font-mono" 
                                    v-model="form.cnic" @input="formatCnic" required placeholder="00000-0000000-0" />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.cnic" />
                        </div>

                        <!-- State -->
                        <div>
                            <InputLabel for="dealer_state" class="text-xs font-semibold text-slate-600 mb-1.5">
                                State <span class="text-red-400">*</span>
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input id="dealer_state" type="text" 
                                    class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none placeholder:text-slate-300" 
                                    v-model="form.dealer_state" required placeholder="Punjab" />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_state" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <InputLabel for="dealer_phone" class="text-xs font-semibold text-slate-600 mb-1.5">
                                Phone
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input id="dealer_phone" type="text" 
                                    class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none placeholder:text-slate-300" 
                                    v-model="form.dealer_phone" placeholder="0300-1234567" />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_phone" />
                        </div>

                        <!-- Relation -->
                        <div>
                            <InputLabel for="dealer_relation" class="text-xs font-semibold text-slate-600 mb-1.5">
                                Relation
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <select id="dealer_relation" 
                                    class="w-full h-10 pl-9 pr-8 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none appearance-none cursor-pointer" 
                                    v-model="form.dealer_relation">
                                    <option value="" disabled selected>Select</option>
                                    <option value="S/O">S/O</option>
                                    <option value="D/O">D/O</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_relation" />
                        </div>

                        <!-- Father Name -->
                        <div>
                            <InputLabel for="dealer_father" class="text-xs font-semibold text-slate-600 mb-1.5">
                                Father's Name
                            </InputLabel>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="dealer_father" type="text" 
                                    class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all outline-none placeholder:text-slate-300" 
                                    v-model="form.dealer_father" placeholder="Ali Raza" />
                            </div>
                            <InputError class="mt-1 text-xs" :message="form.errors.dealer_father" />
                        </div>
                    </div>

                    <!-- Authorize - Compact -->
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" 
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-400/20 focus:ring-offset-0 transition-all cursor-pointer" 
                                :checked="form.Authorize" 
                                @change="form.Authorize = $event.target.checked ? 1 : 0">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">
                                Authorize Dealer
                            </span>
                            <span class="text-xs text-slate-400 font-normal">(Allow portal access)</span>
                        </label>
                    </div>

                    <!-- Actions - Compact -->
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <Link :href="route('dealers.index')" 
                            class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all duration-200">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white text-sm font-semibold rounded-lg shadow-sm shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <svg v-if="form.processing" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>