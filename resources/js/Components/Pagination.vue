<template>
    <nav
        v-if="links.length > 3"
        class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row"
        aria-label="Page navigation"
    >
        <div class="text-sm text-dark-500">
            Page {{ meta?.current_page ?? 1 }} of {{ meta?.last_page ?? 1 }}
            <template v-if="meta">
                <span class="text-dark-400">·</span>
                {{ meta.from }}-{{ meta.to }} of {{ meta.total }}
            </template>
        </div>

        <ul class="inline-flex flex-wrap items-center justify-center gap-1">
            <li v-for="(link, index) in links" :key="index">
                <span
                    v-if="isEllipsis(link.label)"
                    class="px-2 py-1 text-sm text-dark-400 select-none"
                    v-html="link.label"
                />

                <button
                    v-else-if="link.active"
                    type="button"
                    :aria-current="true"
                    v-html="link.label"
                    class="px-3.5 py-1.5 text-sm font-semibold rounded-xl bg-primary-600 text-white"
                />

                <button
                    v-else-if="link.url"
                    type="button"
                    @click="visit(link.url)"
                    v-html="link.label"
                    class="px-3.5 py-1.5 text-sm rounded-xl text-dark-600 hover:bg-dark-100 hover:text-dark-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors"
                />

                <span
                    v-else
                    v-html="link.label"
                    class="px-3.5 py-1.5 text-sm rounded-xl text-dark-300"
                />
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
    meta: {
        type: Object,
        default: null,
    },
});

function isEllipsis(label) {
    return label === '...' || label === '…';
}

function visit(url) {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>
