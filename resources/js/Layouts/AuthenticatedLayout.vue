<script setup>
import { ref, computed, h, onMounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);
const sidebarStorageKey = 'invoice-portal-sidebar-collapsed';

const navSections = [
    {
        label: 'Main',
        items: [
            { name: 'Dashboard', href: route('dashboard'), icon: 'home', routeName: 'dashboard' },
        ],
    },
    {
        label: 'Management',
        items: [
            { name: 'Dealers', href: route('dealers.index'), icon: 'users', routeName: 'dealers.index' },
            { name: 'Blocks', href: route('blocks.index'), icon: 'document', routeName: 'blocks.index' },
            { name: 'Block Roles', href: route('block-roles.index'), icon: 'users', routeName: 'block-roles.index' },
            { name: 'Forms', href: route('forms.index'), icon: 'document', routeName: 'forms.index' },
            { name: 'Open To Byname', href: route('invoices.index'), icon: 'document', routeName: 'invoices.index' },
            // { name: 'Merger Types', href: route('invoice-merger-types.index'), icon: 'document', routeName: 'invoice-merger-types.index' },
            { name: 'Mergers', href: route('mergers.index'), icon: 'document', routeName: 'mergers.index' },
            { name: 'APR', href: route('aprs.index'), icon: 'document', routeName: 'aprs.index' },
        ],
    },
];

const pageTitles = {
    Dashboard: 'Dashboard',
    'Blocks/Index': 'Blocks',
    'Blocks/Create': 'Create Block',
    'Blocks/Edit': 'Edit Block',
    'BlockRoles/Index': 'Block Roles',
    'BlockRoles/Create': 'Create Block Role',
    'BlockRoles/Edit': 'Edit Block Role',
    'Forms/Index': 'Forms',
    'Forms/Create': 'Create Form',
    'Forms/Edit': 'Edit Form',
    'Forms/Show': 'Form Details',
    'Dealers/Index': 'Dealers',
    'Dealers/Create': 'Create Dealer',
    'Dealers/Edit': 'Edit Dealer',
    'Invoices/Index': 'Invoices',
    'Invoices/Create': 'Create Invoice',
    'Mergers/Index': 'Mergers',
    'Mergers/Create': 'Create Merger',
    'Aprs/Index': 'APR',
    'Aprs/Create': 'Create APR',
};

const currentPage = computed(() => {
    const component = page.props.component;
    return (component && pageTitles[component]) ? pageTitles[component] : 'Dashboard';
});

const desktopSidebarClass = computed(() => sidebarCollapsed.value ? 'lg:w-16' : 'lg:w-60');
const desktopContentClass = computed(() => sidebarCollapsed.value ? 'lg:pl-16' : 'lg:pl-60');

onMounted(() => {
    sidebarCollapsed.value = window.localStorage.getItem(sidebarStorageKey) === 'true';
});
watch(sidebarCollapsed, v => window.localStorage.setItem(sidebarStorageKey, String(v)));

// icon map
const iconPaths = {
    home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    document: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    users: 'M17 20h5v-2a3 3 0 00-3-3h-2m-6 5H7a2 2 0 01-2-2v-2a3 3 0 013-3h4a3 3 0 013 3v2a2 2 0 01-2 2m-2-5a3 3 0 11-6 0 3 3 0 016 0z',
    cog: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
};

const NavIcon = {
    props: { name: { type: String }, className: { type: String, default: '' } },
    setup(props) {
        return () => h('svg', {
            class: props.className,
            fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24',
        }, h('path', {
            'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '1.8',
            d: iconPaths[props.name] || '',
        }));
    }
};

function isActive(routeName) {
    try { return route().current(routeName); } catch { return false; }
}
</script>

<template>
    <div class="min-h-screen" style="background: #f0f2f5;">

        <!-- Mobile overlay -->
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden bg-black/40 backdrop-blur-sm"
            @click="sidebarOpen = false" />

        <!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
        <aside :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            desktopSidebarClass,
            'fixed inset-y-0 left-0 z-50 w-60 transform transition-all duration-300 ease-in-out lg:translate-x-0',
        ]" style="background: #1c2431;" aria-label="Sidebar">
            <div class="flex h-full flex-col">

                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center gap-2.5 px-5 border-b border-[#2a3447]">
                    <Link :href="route('dashboard')" class="flex items-center gap-2.5 min-w-0">
                        <div class="h-9 w-9 flex-shrink-0 flex items-center justify-center rounded-lg"
                            style="background: #3b71ca;">
                            <ApplicationLogo class="h-5 w-5 fill-white" />
                        </div>
                        <span v-if="!sidebarCollapsed" class="text-base font-bold text-white truncate">
                            Invoice Portal
                        </span>
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <template v-for="section in navSections" :key="section.label">
                        <!-- Section header -->
                        <p v-if="!sidebarCollapsed" class="sidebar-section">
                            {{ section.label }}
                        </p>
                        <div v-else class="mt-4 mb-1 border-t border-[#2a3447]" />

                        <template v-for="item in section.items" :key="item.name">
                            <Link :href="item.href" :class="[
                                isActive(item.routeName) ? 'sidebar-link-active' : 'sidebar-link',
                                sidebarCollapsed ? 'justify-center px-0' : '',
                                'mb-0.5',
                            ]" :title="sidebarCollapsed ? item.name : undefined" @click="sidebarOpen = false">
                                <NavIcon :name="item.icon" :className="'h-5 w-5 flex-shrink-0'" />
                                <span v-if="!sidebarCollapsed" class="truncate">{{ item.name }}</span>
                            </Link>
                        </template>
                    </template>
                </nav>

                <!-- Bottom user card -->
                <div class="p-3 border-t border-[#2a3447]">
                    <div :class="[
                        sidebarCollapsed ? 'justify-center' : '',
                        'flex items-center gap-3 rounded-xl p-2.5 hover:bg-[#2b374c] transition-colors cursor-pointer',
                    ]">
                        <div class="h-8 w-8 flex-shrink-0 rounded-full flex items-center justify-center text-white text-sm font-semibold"
                            style="background: #3b71ca;">
                            {{ page.props.auth.user?.name?.charAt(0).toUpperCase() }}
                        </div>
                        <div v-if="!sidebarCollapsed" class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">
                                {{ page.props.auth.user?.name }}
                            </p>
                            <p class="text-xs text-[#8b99b0] truncate">
                                {{ page.props.auth.user?.email }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- ═══════════════════════ MAIN CONTENT ═══════════════════════ -->
        <div :class="[desktopContentClass, 'transition-all duration-300 ease-in-out min-h-screen flex flex-col']">

            <!-- Top Header -->
            <header class="sticky top-0 z-30 flex h-14 items-center justify-between gap-4 px-4 sm:px-6"
                style="background: #ffffff; border-bottom: 1px solid #eef0f4;">

                <!-- Left: hamburger + collapse + breadcrumb -->
                <div class="flex items-center gap-3">
                    <!-- Mobile hamburger -->
                    <button @click="sidebarOpen = true"
                        class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                        aria-label="Open menu">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop collapse -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                        :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                        <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="h-5 w-5 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-1.5 text-sm">
                        <span class="text-slate-400">Invoice Portal</span>
                        <svg class="h-3.5 w-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="font-medium text-slate-700">{{ currentPage }}</span>
                    </div>
                </div>

                <!-- Right: search + actions + user -->
                <div class="flex items-center gap-2">

                    <!-- Search -->
                    <div class="relative hidden sm:block">
                        <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="search" placeholder="Quick Search..."
                            class="h-8 w-48 rounded-lg pl-9 pr-3 text-xs transition-all focus:outline-none focus:w-60"
                            style="background: #f8fafc; border: 1px solid #eef0f4; color: #475569;"
                            onfocus="this.style.borderColor='#6d5dfc'; this.style.boxShadow='0 0 0 3px rgba(109,93,252,0.1)';"
                            onblur="this.style.borderColor='#eef0f4'; this.style.boxShadow='';" />
                    </div>

                    <!-- Notification -->
                    <button
                        class="relative p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1 right-1 h-1.5 w-1.5 rounded-full bg-red-500" />
                    </button>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-slate-100 mx-1" />

                    <!-- User dropdown -->
                    <Dropdown align="right" width="56">
                        <template #trigger>
                            <button
                                class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-slate-100 transition-colors focus:outline-none">
                                <div class="h-7 w-7 rounded-full flex items-center justify-center text-white text-xs font-semibold"
                                    style="background: linear-gradient(135deg, #6d5dfc, #48cfad);">
                                    {{ page.props.auth.user?.name?.charAt(0).toUpperCase() }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-slate-600">
                                    {{ page.props.auth.user?.name }}
                                </span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="px-3 py-2.5" style="border-bottom: 1px solid #f1f5f9;">
                                <p class="text-sm font-semibold text-slate-800">{{ page.props.auth.user?.name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ page.props.auth.user?.email }}</p>
                            </div>

                            <DropdownLink :href="route('profile.edit')" class="dropdown-item mt-1">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Profile
                            </DropdownLink>

                            <hr class="my-1 border-slate-100" />

                            <DropdownLink :href="route('logout')" method="post" as="button"
                                class="dropdown-item text-red-500 hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page sub-header slot -->
            <header v-if="$slots.header" style="background: #ffffff; border-bottom: 1px solid #eef0f4;">
                <div class="mx-auto max-w-full px-4 py-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 mx-auto w-full max-w-full px-4 py-5 sm:px-6 lg:px-8">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="py-3 px-6 text-center text-xs text-slate-400"
                style="border-top: 1px solid #eef0f4; background: #ffffff;">
                © {{ new Date().getFullYear() }} Invoice Portal
            </footer>
        </div>
    </div>
</template>
