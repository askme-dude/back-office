<script setup>
import { onMounted } from "vue";

defineProps(["modelValue"]);

const emit = defineEmits(["update:modelValue"]);

onMounted(() => {
    const per_page = route().params.per_page;

    if (per_page) {
        emit("update:modelValue", per_page);
    }
});

const options = [15, 30, 50, 100];
</script>

<template>
    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="select select-bordered"
    >
        <option v-for="opt in options" :key="opt">{{ opt }}</option>
    </select>
</template>
