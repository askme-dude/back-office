<script setup>
import MainCard from "@/Components/MainCard.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { useQuery } from "@/Composables/tablequery.ts";
import { useLocaleDate } from "@/Composables/filters.ts";
import { useConfirm } from "@/Composables/sweetalert.ts";
import PerPageOption from "@/Components/PerPageOption.vue";
import HeadColumn from "@/Components/HeadColumn.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import Pagination from "@/Components/Pagination.vue";
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
import ShowPendidikan from "@/Pages/Pegawai/PegawaiRiwayatPendidikan/components/ShowPendidikan.vue";

const props = defineProps(["title", "riwayatPendidikan"]);

const columns = [
    { label: "Pegawai", column: "pegawai" },
    { label: "Pendidikan", column: "pendidikan" },
    { label: "Nama Instansi", column: "nama_instansi" },
    { label: "No Ijazah", column: "no_ijazah" },
    { label: "Tanggal Ijazah", column: "tanggal_ijazah" },
];
const filterBy = ref({ label: "Pegawai", column: "pegawai" });
const keyword = ref("");
const sortBy = ref(null);
const perPage = ref(15);
const query = useQuery({ keyword, filterBy, sortBy, perPage });
const fetchData = (params = {}) => {
    router.get(
        route("riwayat-pendidikan.index", {
            _query: { ...query.value, ...params },
        }),
        {},
        {
            only: ["riwayatPendidikan"],
            replace: true,
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const selectedRiwayat = ref(null);
const openModal = ref(false);

const showDetail = (data) => {
    router.get(
        route("riwayat-pendidikan.index", { _query: { riwayat_id: data.id } }),
        {},
        {
            only: ["selectedRiwayat", "mediaIjazahUrl"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onSuccess: (page) => {
                selectedRiwayat.value = page.props.selectedRiwayat;
                openModal.value = true;
            },
        },
    );
};

const destroy = async (data) => {
    const confirmed = await useConfirm({
        text: "Hapus data riwayat pendidikan atas nama " + data.pegawai.nama,
    });

    router.delete(route("riwayat-pendidikan.destroy", data), {
        replace: true,
        preserveScroll: true,
        onBefore: () => confirmed,
    });
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

    <ShowPendidikan
        v-if="selectedRiwayat"
        :riwayat="selectedRiwayat"
        v-model:show="openModal"
    />

    <MainCard :title="title">
        <div class="mt-8 grid gap-2 md:flex md:justify-between">
            <div>
                <Link
                    class="btn btn-primary btn-outline"
                    :href="route('riwayat-pendidikan.create')"
                    >Rekam Riwayat Pendidikan
                </Link>
            </div>
            <div class="flex gap-2">
                <SearchInputColumn
                    :search="() => fetchData()"
                    v-model:keyword="keyword"
                    v-model:selected="filterBy"
                    :options="columns"
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
                    <tr v-if="riwayatPendidikan.data.length < 1">
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="(row, i) in riwayatPendidikan.data"
                        :key="row.id"
                    >
                        <td>{{ riwayatPendidikan.from + i }}</td>
                        <td>{{ row.pegawai.nama }}</td>
                        <td>{{ row.pendidikan.nama }}</td>
                        <td>{{ row.nama_instansi }}</td>
                        <td>{{ row.no_ijazah }}</td>
                        <td>
                            {{ useLocaleDate(new Date(row.tanggal_ijazah)) }}
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <PencilSquareIcon
                                    class="h-5 w-5 cursor-pointer text-primary"
                                    @click="
                                        router.get(
                                            route(
                                                'riwayat-pendidikan.edit',
                                                row,
                                            ),
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
        </div>
        <div class="mt-4 grid gap-2 md:flex md:justify-between">
            <ShowingResultTable
                :total="riwayatPendidikan.total"
                :to="riwayatPendidikan.to"
                :from="riwayatPendidikan.from"
            />
            <Pagination
                :links="riwayatPendidikan.links"
                @goToPage="(page) => fetchData({ page })"
            />
        </div>
    </MainCard>
</template>
