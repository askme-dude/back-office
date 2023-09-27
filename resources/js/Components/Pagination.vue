<script setup>
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
} from "@heroicons/vue/24/outline/index.js";
import { computed } from "vue";

const props = defineProps({
    links: Array,
});

const emit = defineEmits(["goToPage"]);
const activeLink = computed(() => props.links.find((l) => l.active));

const goToPage = (link) => {
    let pageNumber;

    if (link.url === null) {
        return;
    }

    if (link.label.includes("previous")) {
        pageNumber = Number(activeLink.value.label) - 1;
    } else if (link.label.includes("next")) {
        pageNumber = Number(activeLink.value.label) + 1;
    } else {
        pageNumber = link.label;
    }

    emit("goToPage", pageNumber);
};
</script>

<template>
    <div class="join">
        <button
            class="btn join-item btn-sm"
            :class="link.active ? 'btn-primary' : ''"
            v-for="link in links"
            :key="link.label"
            @click="goToPage(link)"
        >
            <template v-if="link.label.includes('previous')">
                <ChevronDoubleLeftIcon class="w-4" />
            </template>
            <template v-else-if="link.label.includes('next')">
                <ChevronDoubleRightIcon class="w-4" />
            </template>
            <template v-else>
                {{ link.label }}
            </template>
        </button>
    </div>
</template>
