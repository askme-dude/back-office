<script setup>
defineProps(["title"]);
</script>

<template>
    <div class="rounded-lg bg-base-100 shadow-xl">
        <div class="p-8">
            <h2 class="text-xl font-bold">{{ title }}</h2>
            <slot />
        </div>
    </div>
</template>
