<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    mergerType: Object,
});

const form = useForm({
    name: props.mergerType.name,
    status: props.mergerType.status,
});

function submit() {
    form.put(route('invoice-merger-types.update', props.mergerType.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit Invoice Merger Type" />

        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('invoice-merger-types.index')"
                            class="btn-icon rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Invoice Merger Type</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8 animate-slide-up stagger-1">
                <div class="card overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <h2 class="text-base font-semibold text-slate-800">Merger Type Information</h2>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-1 gap-6">
                        <div>
                            <InputLabel for="name" value="Name *" class="label" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                            <InputError class="mt-1.5" :message="form.errors.name" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 pb-12 pr-8">
                        <Link :href="route('invoice-merger-types.index')" class="btn-secondary btn-lg">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing" class="btn-primary btn-lg min-w-[160px]">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ form.processing ? 'Saving...' : 'Update' }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
