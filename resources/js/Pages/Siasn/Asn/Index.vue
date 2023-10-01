<script setup>
import MainCard from "@/Components/MainCard.vue";
import { EyeIcon } from "@heroicons/vue/24/outline/index.js";
import Pagination from "@/Components/Pagination.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import { ref } from "vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import { Link, router } from "@inertiajs/vue3";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import { useLocaleDateTime } from "@/Composables/filters.ts";
import { useQuery } from "@/Composables/tablequery.ts";
import HeadColumn from "@/Components/HeadColumn.vue";
import BodyRow from "@/Components/BodyRow.vue";

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

const isLoading = ref(false);

const filterBy = ref({ label: "Nama ASN", column: "nama" });
const keyword = ref("");
const perPage = ref(15);
const sortBy = ref(null);
const query = useQuery({ keyword, filterBy, sortBy, perPage });

const fetchData = (param = {}) => {
    router.get(
        route("siasn.asn.index", {
            _query: { ...query.value, ...param },
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
</script>

<template>
    <Head title="Data ASN - SIASN" />

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
                    <SearchInputColumn
                        :options="columns"
                        v-model:keyword="keyword"
                        v-model:selected="filterBy"
                        :search="() => fetchData()"
                    />
                    <PerPageOption
                        v-model="perPage"
                        @change="() => fetchData()"
                    />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <HeadColumn
                                v-for="col in columns"
                                v-model="sortBy"
                                :key="col.label"
                                :content="col"
                                @click="() => fetchData()"
                            />
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="asn.data.length < 1">
                            <td colspan="9" class="text-center">
                                Data tidak ditemukan
                            </td>
                        </tr>
                        <tr v-else v-for="(row, i) in asn.data" :key="row.id">
                            <td>{{ asn.from + i }}</td>
                            <BodyRow
                                v-for="col in columns"
                                :data="row"
                                :content="col"
                            />
                            <td>
                                <div
                                    class="tooltip tooltip-left"
                                    data-tip="Detil"
                                >
                                    <Link
                                        :href="route('siasn.asn.show', row.id)"
                                    >
                                        <EyeIcon class="w-5 text-primary" />
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
