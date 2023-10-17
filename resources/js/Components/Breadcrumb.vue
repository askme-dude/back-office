<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

type BreadCrumbItem = {
    label: string;
    url?: string;
};

defineProps<{
    lists: Array<BreadCrumbItem>;
}>();
</script>

<template>
    <div class="breadcrumbs text-sm">
        <ul>
            <li v-for="(item, i) in lists" :key="item.label">
                <Link v-if="i + 1 !== lists.length" :href="item.url">
                    {{ item.label }}
                </Link>
                <span v-else class="text-info">
                    {{ item.label }}
                </span>
            </li>
        </ul>
    </div>
</template>
