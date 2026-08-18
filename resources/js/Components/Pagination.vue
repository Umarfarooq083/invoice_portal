<template>
    <nav
        v-if="links.length > 3"
        class="flex items-center gap-1"
        aria-label="Pagination"
    >
        <li v-for="(link, index) in links" :key="index" class="list-none">
            <!-- Ellipsis -->
            <span
                v-if="isEllipsis(link.label)"
                class="px-2 py-1 text-xs text-slate-400 select-none"
                v-html="link.label"
            />

            <!-- Active page -->
            <button
                v-else-if="link.active"
                type="button"
                v-html="link.label"
                class="min-w-[30px] h-7 px-2.5 text-xs font-semibold rounded-lg text-white transition-colors"
                style="background: #6d5dfc;"
            />

            <!-- Clickable -->
            <button
                v-else-if="link.url"
                type="button"
                @click="visit(link.url)"
                v-html="link.label"
                class="min-w-[30px] h-7 px-2.5 text-xs rounded-lg text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-700 focus:outline-none transition-colors"
            />

            <!-- Disabled -->
            <span
                v-else
                v-html="link.label"
                class="min-w-[30px] h-7 px-2.5 text-xs rounded-lg text-slate-300 bg-slate-50 inline-flex items-center justify-center"
            />
        </li>
    </nav>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    links: { type: Array, required: true },
    meta: { type: Object, default: null },
});

function isEllipsis(label) {
    return label === '...' || label === '…';
}

function visit(url) {
    if (!url) return;
    router.visit(url, { preserveState: true, preserveScroll: true });
}
</script>
