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
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import { useLocaleDateTime } from "@/Composables/filters.ts";

defineProps(["asn"]);

const columns = [
    { label: "Nama ASN", column: "nama" },
    { label: "NIP", column: "nipBaru" },
    { label: "Golongan", column: "golRuangAkhir" },
    { label: "Jabatan", column: "jabatanNama" },
    { label: "Unit Kerja", column: "unorNama" },
    { label: "Status", column: "kedudukanPnsNama" },
    {
        label: "Terkahir Update",
        column: "updated_at",
        cell: (val) => useLocaleDateTime(new Date(val)),
    },
];
const filterBy = ref({ label: "Nama ASN", column: "nama" });
const keyword = ref("");
const perPage = ref(15);
const sortBy = ref(null);
const isLoading = ref(false);
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
        route("siasn-asn.index", {
            _query: { ...query.value, ...additional },
        }),
        {},
        {
            only: ["asn"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const syncAllData = () => {
    router.post(
        route("fetch-all-pns-data-utama"),
        {},
        {
            only: ["asn", "toast"],
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onStart: () => (isLoading.value = true),
            onFinish: () => (isLoading.value = false),
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
</script>

<template>
    <Head>
        <title>Data ASN - SIASN</title>
    </Head>

    <MainCard title="Data ASN berdasarkan SIASN">
        <!-- TODO: fix responsive mobile view-->
        <div class="mt-8">
            <div class="grid gap-2 md:grid-cols-2 md:justify-items-end">
                <button
                    :disabled="isLoading"
                    class="btn btn-primary btn-outline btn-sm justify-self-start"
                    @click="syncAllData"
                >
                    Sinkronisasi
                    <span class="loading loading-spinner" v-if="isLoading" />
                </button>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in asn.data" :key="row.id">
                            <td>{{ asn.from + i }}</td>
                            <td v-for="col in columns" :key="col.label">
                                <template v-if="col.cell">
                                    {{ col.cell(row[col.column]) }}
                                </template>
                                <template v-else>
                                    {{ row[col.column] }}
                                </template>
                            </td>
                            <td>
                                <div
                                    class="tooltip tooltip-left"
                                    data-tip="Lihat detil"
                                >
                                    <Link
                                        :href="route('siasn-asn.show', row.id)"
                                    >
                                        <DocumentMagnifyingGlassIcon
                                            class="w-5 text-primary"
                                        />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 grid gap-2 md:grid-cols-2 md:justify-items-end">
                <ShowingResultTable
                    :from="asn.from"
                    :to="asn.to"
                    :total="asn.total"
                    class="justify-self-start"
                />

                <Pagination
                    :links="asn.links"
                    @goToPage="(page) => fetchData({ page })"
                />
            </div>
        </div>
    </MainCard>
</template>
