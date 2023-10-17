<script setup lang="ts">
import { ChevronUpDownIcon } from "@heroicons/vue/24/outline/index.js";
import { computed, ref } from "vue";

type Column = {
    label: string;
    column?: string;
};

const props = defineProps<{
    content: Column;
    modelValue: string;
}>();

const result = ref(null);

const emit = defineEmits(["update:modelValue"]);

const sorting = (column: string) => {
    if (result.value === column) {
        result.value = "-" + column;
    } else if (result.value?.includes(column)) {
        result.value = null;
    } else {
        result.value = column;
    }

    emit("update:modelValue", result.value);
};

const isActive = computed(() => {
    return result.value?.includes(props.content.column);
});
</script>

<template>
    <th
        scope="col"
        :class="{ 'text-gray-900': isActive }"
        @click="sorting(content.column)"
    >
        <div class="flex cursor-pointer justify-between">
            {{ content.label }}
            <ChevronUpDownIcon class="w-4" />
        </div>
    </th>
</template>
