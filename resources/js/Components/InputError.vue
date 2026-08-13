<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-if="message" class="mt-2">
        <p class="text-sm text-danger-600">
            {{ message }}
        </p>
    </div>
</template>
