<script setup>
defineProps({
    from: {
        type: Number,
        required: true,
    },
    to: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="text-xs text-gray-700">
        Showing
        {{ " " }}
        <span class="font-medium">{{ from }}</span>
        {{ " " }}
        to
        {{ " " }}
        <span class="font-medium">{{ to }}</span>
        {{ " " }}
        of
        {{ " " }}
        <span class="font-medium">{{ total }}</span>
        {{ " " }}
        results
    </div>
</template>
