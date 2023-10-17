<script setup>
import MainCard from "@/Components/MainCard.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { ref } from "vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import { EyeIcon } from "@heroicons/vue/24/outline/index.js";
import { useLocaleDate } from "@/Composables/filters.ts";

defineProps(["riwayat"]);

const keyword = ref(null);
const perPage = ref(15);

const fetchData = (params = {}) => {
    router.get(
        route("riwayat-penghargaan.index"),
        {
            ...(keyword.value && { filter: { keyword: keyword.value } }),
            per_page: perPage.value,
            ...params,
        },
        {
            only: ["riwayat"],
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};
</script>

<template>
    <Head title="Riwayat Penghargaan" />

    <MainCard title="Daftar Riwayat Penghargaan">
        <div class="mt-8 flex justify-between">
            <div>
                <Link
                    :href="route('riwayat-penghargaan.create')"
                    class="btn btn-primary btn-outline btn-sm"
                >
                    Rekam Data Baru
                </Link>
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
        <div class="mt-2 overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Nama Penghargaan</th>
                        <th>No SK</th>
                        <th>Tanggal SK</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="riwayat.data.length < 1">
                        <td colspan="6" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <tr
                        v-else
                        class="hover"
                        v-for="(row, i) in riwayat.data"
                        :key="i"
                    >
                        <td>{{ riwayat.from + i }}</td>
                        <td>{{ row.pegawai.nama }}</td>
                        <td>{{ row.penghargaan.nama }}</td>
                        <td>{{ row.no_sk }}</td>
                        <td>{{ useLocaleDate(new Date(row.tanggal_sk)) }}</td>
                        <td>{{ row.tahun }}</td>
                        <td>
                            <Link
                                :href="
                                    route('riwayat-penghargaan.show', row.id)
                                "
                            >
                                <EyeIcon class="h-5 w-5 text-primary" />
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between">
            <ShowingResultTable
                :from="riwayat.from"
                :to="riwayat.to"
                :total="riwayat.total"
            />
            <Pagination
                :links="riwayat.links"
                @goToPage="(page) => fetchData({ page })"
            />
        </div>
    </MainCard>
</template>
