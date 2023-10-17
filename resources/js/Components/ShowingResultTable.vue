<script setup>
defineProps({
    from: Number,
    to: Number,
    total: Number,
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
