<script setup>
import {
    MagnifyingGlassIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline/index.js";
import { computed } from "vue";

const props = defineProps({
    placeholder: {
        type: String,
        default: "Cari",
    },
    options: Array,
    keyword: String,
    selected: Object,
});

const emit = defineEmits(["update:keyword", "update:selected"]);

const selectedColumn = computed({
    get() {
        return props.selected;
    },
    set(value) {
        return emit("update:selected", value);
    },
});
</script>

<template>
    <div class="join">
        <select
            v-model="selectedColumn"
            class="select join-item select-bordered"
        >
            <option :value="opt" :key="opt.label" v-for="opt in options">
                {{ opt.label }}
            </option>
        </select>
        <div class="join-item relative">
            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
            >
                <MagnifyingGlassIcon class="w-4" />
            </div>
            <input
                :value="keyword"
                type="text"
                class="input input-bordered input-md w-full rounded-l-none pl-10"
                :placeholder="placeholder"
                @input="$emit('update:keyword', $event.target.value)"
            />
            <div
                v-if="keyword"
                class="absolute inset-y-2.5 right-1 flex cursor-pointer items-center rounded-full p-2 hover:bg-base-200"
                @click="$emit('update:keyword', '')"
            >
                <XMarkIcon class="w-3" />
            </div>
        </div>
    </div>
</template>
