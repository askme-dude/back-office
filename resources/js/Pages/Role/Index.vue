<script setup>
import MainCard from "@/Components/MainCard.vue";
const props = defineProps({
    roles: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
    title:String
});
</script>
<template>
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="/">Beranda</a></li>
            <li><a href="/role">Roles</a></li>
        </ul>
    </div>

    <MainCard :title="title">
        <div class="overflow-x-auto">
            <div class="py-4">
                <div
                    class="flex items-center space-x-2"
                    v-if="can.create">
                    <Link :href="route('role.create')">
                        <button
                            class="btn btn-primary">
                            <span
                                class="iconify mr-1"
                                data-icon="gridicons:create"
                                data-inline="false"
                            ></span>
                            Tambah Role
                        </button>
                    </Link>
                </div>
            </div>

            <div class="mx-auto mb-2 max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table
                        class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th
                                    v-if="can.edit || can.delete"
                                    scope="col"
                                    class="px-6 py-3"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="role in roles.data"
                                :key="role.id"
                                class="border-b bg-white dark:border-gray-700 dark:bg-gray-800"
                            >
                                <td data-label="Name" class="px-6 py-4">
                                    {{ role.name }}
                                </td>
                                <td
                                    v-if="can.edit || can.delete"
                                    class="px-6 py-4"
                                >
                                    <div
                                        type="justify-start lg:justify-end"
                                        no-wrap
                                    >
                                        <BreezeButton
                                            class="ml-4 cursor-pointer rounded bg-green-500 px-2 py-1 text-white"
                                            v-if="can.edit"
                                        >
                                            Edit
                                        </BreezeButton>
                                        <BreezeButton
                                            class="ml-4 cursor-pointer rounded bg-red-500 px-2 py-1 text-white"
                                            v-if="can.delete"
                                        >
                                            Delete
                                        </BreezeButton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </MainCard>
</template>
