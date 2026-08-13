<script setup>
import { ref, computed, h } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const sidebarOpen = ref(false);

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), icon: 'home', routeName: 'dashboard' },
];

const pageTitles = {
    Dashboard: 'Dashboard',
    Invoices: 'Invoices',
    Clients: 'Clients',
    Products: 'Products',
    Reports: 'Reports',
    Settings: 'Settings',
};

const currentPage = computed(() => {
    const component = page.props.component;
    if (component && pageTitles[component]) {
        return pageTitles[component];
    }
    return 'Dashboard';
});

const iconPaths = {
    home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    document: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    users: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    cube: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    chart: 'M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6',
    cog: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
};

const NavIcon = {
    props: { name: { type: String, required: true }, className: { type: String, default: '' } },
    setup(props) {
        const path = iconPaths[props.name] || '';
        return () => h('svg', {
            class: props.className,
            fill: 'none',
            stroke: 'currentColor',
            viewBox: '0 0 24 24',
            xmlns: 'http://www.w3.org/2000/svg',
        }, {
            path: h('path', {
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
                'stroke-width': '2',
                d: path,
            }),
        });
    }
};
</script>

<template>
    <div class="min-h-screen bg-dark-50">
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
            aria-hidden="true"
        />

        <aside
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                'fixed inset-y-0 left-0 z-50 w-64 transform bg-white border-r border-dark-200 transition-transform duration-300 ease-in-out lg:translate-x-0',
            ]"
            aria-label="Sidebar"
        >
            <div class="flex h-full flex-col">
                <div class="flex h-16 shrink-0 items-center border-b border-dark-200 px-6">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600">
                            <ApplicationLogo class="h-6 w-6 fill-white" />
                        </div>
                        <span class="text-xl font-bold text-dark-900">InvoicePortal</span>
                    </Link>
                </div>

                <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1" aria-label="Main navigation">
                    <template v-for="item in navigation" :key="item.name">
                        <NavLink
                            :href="item.href"
                            :active="route().current(item.routeName)"
                            class="sidebar-link group"
                        >
                            <NavIcon :name="item.icon" className="h-5 w-5 shrink-0 transition-transform group-hover:translate-x-0.5" />
                            {{ item.name }}
                        </NavLink>
                    </template>
                </nav>

                <div class="border-t border-dark-200 p-4">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ page.props.auth.user?.name?.charAt(0).toUpperCase() }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-dark-900 truncate">
                                {{ page.props.auth.user?.name }}
                            </p>
                            <p class="text-xs text-dark-500 truncate">
                                {{ page.props.auth.user?.email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:pl-64">
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-dark-200">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-xl text-dark-500 hover:bg-dark-100 hover:text-dark-700"
                        aria-label="Open navigation menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 lg:hidden">
                        <h1 class="text-lg font-semibold text-dark-900">{{ currentPage }}</h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:block">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="search"
                                    placeholder="Search..."
                                    class="h-9 w-64 rounded-xl bg-dark-50 border border-dark-200 pl-10 pr-4 text-sm text-dark-900 placeholder:text-dark-400 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 focus:outline-none transition-all"
                                />
                            </div>
                        </div>

                        <button class="relative p-2 rounded-xl text-dark-500 hover:bg-dark-100 hover:text-dark-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-danger-500" />
                        </button>

                        <div class="relative ms-3">
                            <Dropdown align="right" width="64">
                                <template #trigger>
                                    <button
                                        class="flex items-center gap-2 rounded-xl p-1.5 hover:bg-dark-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                    >
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                {{ page.props.auth.user?.name?.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                        <span class="hidden sm:block text-sm font-medium text-dark-700">
                                            {{ page.props.auth.user?.name }}
                                        </span>
                                        <svg class="hidden sm:block h-4 w-4 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="px-3 py-2 border-b border-dark-200">
                                        <p class="text-sm font-medium text-dark-900">{{ page.props.auth.user?.name }}</p>
                                        <p class="text-xs text-dark-500">{{ page.props.auth.user?.email }}</p>
                                    </div>
                                    <DropdownLink :href="route('profile.edit')" class="dropdown-item">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profile
                                    </DropdownLink>
                                   
                                    <hr class="my-2 border-dark-200" />
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="dropdown-item text-danger-600"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l9-9-9-9M21 12h-6m0 0v6m0-6V9" />
                                        </svg>
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <header v-if="$slots.header" class="bg-white border-b border-dark-200">
                <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
