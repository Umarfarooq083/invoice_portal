<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full rounded-xl px-4 py-3 text-base font-medium text-primary-700 bg-primary-50 border-l-4 border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-200'
        : 'block w-full rounded-xl px-4 py-3 text-base font-medium text-dark-600 hover:text-dark-900 hover:bg-dark-100 border-l-4 border-transparent focus:outline-none focus:ring-2 focus:ring-dark-300 transition-all duration-200',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
