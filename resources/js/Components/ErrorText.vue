<script setup>
defineProps(["text"]);
</script>

<template>
    <div v-if="text" class="text-xs italic text-error">
        {{ text }}
    </div>
</template>
