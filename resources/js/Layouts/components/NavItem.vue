<script setup>
import useRouteStore from "@/Stores/RouteStore.js";
import { usePage } from '@inertiajs/vue3';
import { computed } from "vue";
const page = usePage()

defineProps(["item"]);

const routes = useRouteStore();

const akses = computed(() => page.props.auth.akses)

function checkPermission(strPermission, permission) {
    if (permission[strPermission]){
        return true;
    }else{
        return false;
    }

}

</script>

<template>

    <li v-if="routes.isHasAccess(item,akses) && item.children">

        <details :open="false">
            <summary>
                <component :is="item.icon" class="mr-2 w-6" />
                <span class="font-semibold">{{ item.label }}</span>
            </summary>
            <NavItem
                class="pl-4"
                v-for="row in item.children"
                :key="row.label"
                :item="row"
                :v-if="checkPermission(row.label,akses)"
            />
        </details>

    </li>
    <li v-else-if="routes.isHasAccess(item, akses)">
        <Link :href="item.href">
            <component :is="item.icon" class="mr-2 w-6" />
            <span class="font-semibold">{{ item.label }}</span>
        </Link>
    </li>
</template>
