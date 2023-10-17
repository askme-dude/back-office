<script setup>
import MainCard from "@/Components/MainCard.vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { useLocaleDateTime } from "@/Composables/filters.ts";
import SearchInput from "@/Components/SearchInput.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import {
    ArrowDownTrayIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline/index.js";

defineProps(["penghargaan"]);

const keyword = ref(null);
const perPage = ref(15);

const fetchData = (params = {}) => {
    router.get(
        route("siasn-penghargaan.index"),
        {
            ...(keyword.value && { filter: { pegawai: keyword.value } }),
            per_page: perPage.value,
            ...params,
        },
        {
            only: ["penghargaan"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const synchronize = () => {
    router.get(route("fetch-all-rw-penghargaan"));
};

const syncItem = (riwayat_id) => {
    router.get(
        route("fetch-pns-rw-penghargaan", riwayat_id),
        {},
        {
            preserveScroll: true,
            replace: true,
        },
    );
};

const downloadUrl = (path) => {
    let filePath = path[0]?.dok_uri ? path[0].dok_uri : path[892]?.dok_uri;

    if (!filePath) {
        return;
    }

    return route("siasn-download-file", {
        _query: { filePath },
    });
};
</script>

<template>
    <Head title="Penghargaan" />

    <MainCard title="Daftar Riwayat Penghargaan Data SIASN">
        <div class="mt-8 flex justify-between">
            <div>
                <button
                    class="btn btn-primary btn-outline btn-sm"
                    @click="synchronize"
                >
                    Sinkronisasi
                </button>
            </div>
            <div class="flex gap-2">
                <SearchInput
                    v-model="keyword"
                    :search="fetchData"
                    placeholder="Cari Nama Pegawai"
                />
                <PerPageOption v-model="perPage" @change="() => fetchData()" />
            </div>
        </div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Nama Pegawai</td>
                        <td>Nama Penghargaan</td>
                        <td>Tahun</td>
                        <td>Nomor SK</td>
                        <td>Tanggal SK</td>
                        <td>Terakhir Update</td>
                        <td>Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="penghargaan.data.length < 1">
                        <td colspan="6">Data tidak ditemukan</td>
                    </tr>
                    <tr v-else v-for="(row, i) in penghargaan.data" :key="i">
                        <td>{{ penghargaan.from + i }}</td>
                        <td>{{ row.siasn_pegawai?.nama }}</td>
                        <td>{{ row.penghargaan.nama }}</td>
                        <td>{{ row.tahun }}</td>
                        <td>{{ row.skNomor }}</td>
                        <td>{{ row.skDate }}</td>
                        <td>
                            {{ useLocaleDateTime(new Date(row.updated_at)) }}
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a
                                    download="penghargaan"
                                    v-if="downloadUrl(row.path)"
                                    :href="downloadUrl(row.path)"
                                >
                                    <ArrowDownTrayIcon
                                        class="h-5 w-5 text-primary"
                                    />
                                </a>
                                <ArrowPathIcon
                                    class="h-5 w-5 cursor-pointer text-success"
                                    @click="syncItem(row.id)"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between">
            <ShowingResultTable
                :to="penghargaan.to"
                :from="penghargaan.from"
                :total="penghargaan.total"
            />
            <Pagination
                :links="penghargaan.links"
                @goToPage="(page) => fetchData({ page })"
            />
        </div>
    </MainCard>
</template>
