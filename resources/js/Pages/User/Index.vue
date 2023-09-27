<script setup>
import MainCard from "@/Components/MainCard.vue";
import {
    ChevronUpDownIcon,
    DocumentMagnifyingGlassIcon,
} from "@heroicons/vue/24/outline/index.js";
import Pagination from "@/Components/Pagination.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import { computed, ref, watch } from "vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import { router, Link } from "@inertiajs/vue3";
import { debounce } from "lodash";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";

defineProps(["user"]);

const columns = [
    { label: "Pengguna", column: "name" },
    { label: "email", column: "email" },
];
const filterBy = ref({ label: "Nama Pengguna", column: "name" });
const keyword = ref("");
const perPage = ref(15);
const sortBy = ref(null);
const query = computed(() => {
    return {
        ...(keyword.value && {
            filter: {
                [filterBy.value.column]: keyword.value,
            },
        }),
        ...(sortBy.value && { sort: sortBy.value }),
        ...(perPage.value && { per_page: perPage.value }),
    };
});

watch(
    keyword,
    debounce(() => fetchData(), 200),
);

watch(perPage, () => fetchData());

const fetchData = (additional) => {
    router.get(
        route("user.index", {
            _query: { ...query.value, ...additional },
        }),
        {},
        {
            only: ["user"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const sort = (column) => {
    if (sortBy.value === column.column) {
        sortBy.value = "-" + column.column;
    } else if (sortBy.value?.includes(column.column)) {
        sortBy.value = null;
    } else {
        sortBy.value = column.column;
    }

    fetchData();
};

const edit = (id) => {
    router.get(route('user.edit',id));
}

</script>

<template>
    <Head title="Data Pengguna" />

    <MainCard title="Data Pengguna">
        <!-- TODO: fix responsive mobile view-->
        <div class="mt-8">
            <div class="grid gap-2 md:grid-cols-2 md:justify-items-end">

                <Link
                    class="justify-self-start"
                    :href="route('user.create')"
                    v-if="$page.props.auth.akses.gaji_create">
                    <button class="btn btn-primary">Tambah</button>
                </Link>

                <div class="flex gap-2">
                    <!-- Use this if doesnt need filter by column-->
                    <!-- <SearchInput class="w-64" v-model="keyword" />-->
                    <SearchInputColumn
                        :options="columns"
                        v-model:keyword="keyword"
                        v-model:selected="filterBy"
                    />
                    <PerPageOption v-model="perPage" />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th
                                v-for="col in columns"
                                :key="col.label"
                                :class="{
                                    'text-gray-900': sortBy?.includes(
                                        col.column,
                                    ),
                                }"
                            >
                                <div
                                    class="flex cursor-pointer justify-between"
                                    @click="sort(col)"
                                >
                                    {{ col.label }}
                                    <ChevronUpDownIcon class="w-4" />
                                </div>
                            </th>
                            <th v-if="$page.props.auth.akses.pengguna_edit || $page.props.auth.akses.pengguna_delete">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in user.data" :key="row.id">
                            <td>{{ user.from + i }}</td>
                            <td v-for="col in columns" :key="col.label">
                                {{ row[col.column] }}
                            </td>

                            <td v-if="$page.props.auth.akses.pengguna_edit || $page.props.auth.akses.pengguna_delete">
                                <button
                                    @click.prevent="edit(row.id)"
                                    class="btn btn-primary btn-xs mr-2 tooltip" data-tip="Edit Data Pengguna"
                                    v-if="$page.props.auth.akses.pengguna_edit">Edit
                                </button>
                                <button
                                    v-if="$page.props.auth.akses.pengguna_delete"
                                    class="btn btn-error btn-xs tooltip" data-tip="Hapus Data Pengguna"
                                    @click="destroy(row.id)">
                                    Hapus
                                </button>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 grid gap-2 md:grid-cols-2 md:justify-items-end">
                <ShowingResultTable
                    :from="user.from"
                    :to="user.to"
                    :total="user.total"
                    class="justify-self-start"
                />

                <Pagination
                    :links="user.links"
                    @goToPage="(page) => fetchData({ page })"
                />
            </div>
        </div>
    </MainCard>
</template>
