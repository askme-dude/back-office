<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import NavItem from "@/Layouts/components/NavItem.vue";
import useRouteStore from "@/Stores/RouteStore.js";

const routes = useRouteStore();

const currentRoute = ref(null);

router.on("navigate", () => {
    currentRoute.value = route().current();
});
</script>

<template>
    <nav class="menu min-h-full w-64 border-r bg-base-100">
        <img
            src="/assets/logo.png"
            width="180"
            class="mx-auto mb-6 mt-2"
            alt="logo"
        />
        <ul class="flex flex-col gap-2">
            <NavItem
                v-for="item in routes.list"
                :key="item.label"
                :item="item"
            />
        </ul>
    </nav>
</template>
