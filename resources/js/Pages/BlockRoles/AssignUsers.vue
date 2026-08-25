<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    blockRole: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    }
});

const form = useForm({
    users: props.blockRole.users ? props.blockRole.users.map(u => u.id) : [],
});

const searchAvailable = ref('');
const searchSelected = ref('');

// Filter available users (not selected)
const availableUsers = computed(() => {
    let list = props.users.filter(u => !form.users.includes(u.id));
    if (searchAvailable.value) {
        const query = searchAvailable.value.toLowerCase();
        list = list.filter(u => u.name.toLowerCase().includes(query) || u.email.toLowerCase().includes(query));
    }
    return list.slice(0, 100); // Limit to 100 for performance
});

// Filter selected users
const selectedUsers = computed(() => {
    let list = props.users.filter(u => form.users.includes(u.id));
    if (searchSelected.value) {
        const query = searchSelected.value.toLowerCase();
        list = list.filter(u => u.name.toLowerCase().includes(query) || u.email.toLowerCase().includes(query));
    }
    return list;
});

function selectUser(id) {
    if (!form.users.includes(id)) {
        form.users.push(id);
    }
}

function removeUser(id) {
    const index = form.users.indexOf(id);
    if (index !== -1) {
        form.users.splice(index, 1);
    }
}

function submit() {
    form.post(route('block-roles.sync-users', props.blockRole.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Assign Users" />

        <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
                <div>
                    <div class="flex items-center gap-3">
                        <Link :href="route('block-roles.index')"
                            class="btn-icon rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Assign Users to <span class="text-green-600">{{ blockRole.name }}</span></h1>
                    </div>
                    <p class="mt-2 text-sm text-slate-500 ml-12">Search and transfer users to assign them to this role.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="animate-slide-up stagger-1">
                <div class="card overflow-hidden border border-slate-200 shadow-sm rounded-xl">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white bg-green-500 shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-800">Users Transfer List</h2>
                        </div>
                        <div class="text-sm text-slate-500 font-medium bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                            <span class="text-green-600 font-bold">{{ form.users.length }}</span> Selected
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white">
                        
                        <!-- Available Users List -->
                        <div class="flex flex-col border border-slate-200 rounded-xl overflow-hidden bg-slate-50/30">
                            <div class="p-3 bg-slate-100/50 border-b border-slate-200">
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Available Users</h3>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input v-model="searchAvailable" type="text" placeholder="Search available..." 
                                        class="w-full pl-9 pr-3 py-2 text-sm border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500 shadow-sm" />
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto p-2" style="height: 400px;">
                                <div v-if="availableUsers.length === 0" class="text-center py-10 text-slate-400 text-sm">
                                    No available users found.
                                </div>
                                <div v-else class="space-y-1">
                                    <div v-for="user in availableUsers" :key="user.id" 
                                        @click="selectUser(user.id)"
                                        class="flex items-center justify-between p-3 rounded-lg border border-transparent hover:border-slate-200 hover:bg-white cursor-pointer transition-all group">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-semibold text-xs flex-shrink-0">
                                                {{ user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 truncate">{{ user.name }}</p>
                                                <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="text-slate-300 group-hover:text-green-500 bg-white rounded-full p-1 opacity-0 group-hover:opacity-100 shadow-sm border border-slate-100 transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="searchAvailable === '' && props.users.length - form.users.length > 100" class="text-center py-3 text-xs text-slate-400 border-t border-slate-100 mt-2">
                                    Showing top 100. Use search to find more.
                                </div>
                            </div>
                        </div>

                        <!-- Selected Users List -->
                        <div class="flex flex-col border border-green-200 rounded-xl overflow-hidden bg-green-50/10 shadow-[0_0_15px_rgba(34,197,94,0.05)]">
                            <div class="p-3 bg-green-50/50 border-b border-green-100">
                                <h3 class="text-sm font-semibold text-green-800 mb-2">Assigned Users</h3>
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input v-model="searchSelected" type="text" placeholder="Search assigned..." 
                                        class="w-full pl-9 pr-3 py-2 text-sm border-green-200 rounded-lg focus:ring-green-500 focus:border-green-500 shadow-sm bg-white" />
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto p-2" style="height: 400px;">
                                <div v-if="selectedUsers.length === 0" class="flex flex-col items-center justify-center h-full text-center py-10 text-slate-400 text-sm space-y-3">
                                    <div class="h-12 w-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <p>No users assigned yet.<br/>Click a user from the left list to assign.</p>
                                </div>
                                <div v-else class="space-y-1">
                                    <div v-for="user in selectedUsers" :key="user.id" 
                                        @click="removeUser(user.id)"
                                        class="flex items-center justify-between p-3 rounded-lg border border-green-100 bg-white shadow-sm hover:border-red-200 cursor-pointer transition-all group">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs flex-shrink-0">
                                                {{ user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 truncate">{{ user.name }}</p>
                                                <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                                            </div>
                                        </div>
                                        <button type="button" class="text-red-300 group-hover:text-red-500 bg-red-50 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <InputError :message="form.errors.users" />
                        <div class="flex items-center gap-3 ml-auto">
                            <Link :href="route('block-roles.index')" class="btn-secondary">
                                Cancel
                            </Link>
                            <button type="submit" :disabled="form.processing" class="btn-primary min-w-[140px] !bg-green-600 hover:!bg-green-700">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ form.processing ? 'Saving...' : 'Save Assignments' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
