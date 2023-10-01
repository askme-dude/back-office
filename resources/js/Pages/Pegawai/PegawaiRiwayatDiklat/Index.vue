<script setup>
import MainCard from "@/Components/MainCard.vue";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import { ref } from "vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import HeadColumn from "@/Components/HeadColumn.vue";
import { useQuery } from "@/Composables/tablequery.ts";
import { useConfirm } from "@/Composables/sweetalert.ts";
import { router } from "@inertiajs/vue3";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import { useLocaleDate } from "@/Composables/filters.ts";
import Pagination from "@/Components/Pagination.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
import ShowDiklat from "@/Pages/Pegawai/PegawaiRiwayatDiklat/components/ShowDiklat.vue";

defineProps(["title", "riwayatDiklat"]);

const columns = [
    { label: "Pegawai", column: "pegawai" },
    { label: "Jenis Diklat", column: "jenis_diklat" },
    { label: "Judul", column: "nama" },
    { label: "Tanggal Mulai", column: "tanggal_mulai" },
    { label: "Penyelenggara", column: "penyelenggara" },
];
const filterBy = ref({ label: "Pegawai", column: "pegawai" });
const keyword = ref("");
const perPage = ref(15);
const sortBy = ref("");
const query = useQuery({ keyword, filterBy, sortBy, perPage });
const fetchData = (params = {}) => {
    router.get(
        route("riwayat-diklat.index", {
            _query: { ...query.value, ...params },
        }),
        {},
        {
            only: ["riwayatDiklat"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const destroy = async (diklat) => {
    const confirmed = await useConfirm({
        text:
            "Hapus data riwayat diklat atas nama pegawai " +
            diklat.pegawai.nama,
    });

    router.delete(route("riwayat-diklat.destroy", diklat), {
        replace: true,
        onBefore: () => confirmed,
    });
};

const openModal = ref(false);
const selectedDiklat = ref(null);

const showDetail = (diklat) => {
    router.get(
        route("riwayat-diklat.index", {
            _query: {
                diklat_id: diklat.id,
            },
        }),
        {},
        {
            only: ["media_sertifikat_url"],
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onSuccess: () => {
                selectedDiklat.value = diklat;
                openModal.value = true;
            },
        },
    );
};
</script>

<template>
    <Head :title="title" />

    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Pegawai', url: route('pegawai.index') },
            { label: title },
        ]"
    />

    <ShowDiklat
        v-if="selectedDiklat"
        v-model:show="openModal"
        :diklat="selectedDiklat"
    />

    <MainCard :title="title">
        <div class="mt-8 grid gap-2 md:flex md:justify-between">
            <div>
                <button
                    class="btn btn-primary btn-outline"
                    @click="router.get(route('riwayat-diklat.create'))"
                >
                    Rekam Diklat Baru
                </button>
            </div>
            <div class="flex justify-between gap-2 md:justify-end">
                <SearchInputColumn
                    :options="columns"
                    :search="() => fetchData()"
                    v-model:keyword="keyword"
                    v-model:selected="filterBy"
                />
                <PerPageOption v-model="perPage" @change="() => fetchData()" />
            </div>
        </div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <HeadColumn
                            v-for="col in columns"
                            :key="col.label"
                            v-model="sortBy"
                            :content="col"
                            @click="() => fetchData()"
                        />
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="riwayatDiklat.data.length < 0">
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <tr
                        v-else
                        class="hover"
                        v-for="(row, i) in riwayatDiklat.data"
                        :key="row.id"
                    >
                        <td>{{ riwayatDiklat.from + i }}</td>
                        <td>{{ row.pegawai.nama }}</td>
                        <td>{{ row.jenis_diklat.nama }}</td>
                        <td>{{ row.nama }}</td>
                        <td>
                            {{ useLocaleDate(new Date(row.tanggal_mulai)) }}
                        </td>
                        <td>{{ row.penyelenggara }}</td>
                        <td>
                            <div class="flex gap-1">
                                <PencilSquareIcon
                                    class="h-5 w-5 cursor-pointer text-primary"
                                    @click="
                                        router.get(
                                            route('riwayat-diklat.edit', row),
                                        )
                                    "
                                />
                                <EyeIcon
                                    class="h-5 w-5 cursor-pointer text-warning"
                                    @click="showDetail(row)"
                                />
                                <TrashIcon
                                    class="h-5 w-5 cursor-pointer text-error"
                                    @click="destroy(row)"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 grid gap-2 md:flex md:justify-between">
                <ShowingResultTable
                    :total="riwayatDiklat.total"
                    :to="riwayatDiklat.to"
                    :from="riwayatDiklat.from"
                />
                <Pagination
                    :links="riwayatDiklat.links"
                    @goToPage="(page) => fetchData({ page })"
                />
            </div>
        </div>
    </MainCard>
</template>
