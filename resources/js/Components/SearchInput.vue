<script setup>
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline/index.js";
import { onMounted, watch } from "vue";
import { debounce } from "lodash";

const props = defineProps({
    placeholder: {
        type: String,
        default: "Cari",
    },
    modelValue: {
        type: String,
        required: true,
    },
    search: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

onMounted(() => {
    const params = route().params;

    if (params?.filter) {
        emit("update:modelValue", Object.values(params.filter)[0]);
    }
});

watch(
    () => props.modelValue,
    debounce(() => props.search(), 300),
);
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
