<script setup>
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline/index.js";

defineProps({
    placeholder: {
        type: String,
        default: "Cari",
    },
    modelValue: String,
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="relative">
        <div
            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
        >
            <MagnifyingGlassIcon class="w-4" />
        </div>
        <input
            :value="modelValue"
            type="text"
            class="input input-bordered input-md w-full pl-10"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>
