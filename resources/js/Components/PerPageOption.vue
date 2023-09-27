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
</script>

<template>
    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="select select-bordered"
    >
        <option>15</option>
        <option>30</option>
        <option>50</option>
        <option>100</option>
    </select>
</template>
