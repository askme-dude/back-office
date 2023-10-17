<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import MainCard from "@/Components/MainCard.vue";
import Pagination from "@/Components/Pagination.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import { useQuery } from "@/Composables/tablequery.ts";
import { useConfirm } from "@/Composables/sweetalert.ts";
import HeadColumn from "@/Components/HeadColumn.vue";

const props = defineProps({
    pegawai: Object,
});

const columns = [
    { label: "Nama Pegawai", column: "nama" },
    { label: "NIP", column: "nip" },
    {
        label: "Status",
        column: "status_dinas",
        cell: (val) => (val == 1 ? "Aktif" : "Tidak Aktif"),
    },
];

const filterBy = ref({ label: "Nama Pegawai", column: "nama" });
const keyword = ref("");
const perPage = ref(10);
const sortBy = ref(null);
const query = useQuery({ keyword, filterBy, sortBy, perPage });

const fetchData = (param = {}) => {
    router.get(
        route("profil_pegawai.index", {
            _query: { ...query.value, ...param },
        }),
        {},
        {
            only: ["pegawai"],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const lihatPegawai = (id) => {
    router.get(route("profil_pegawai.show", id));
};

const editPegawai = (id) => {
    router.get(route("profil_pegawai.edit", id));
};

const hapusPegawai = async (id) => {
    const confirmed = await useConfirm({ text: "Hapus data pegawai" });

    router.delete(route("profil_pegawai.destroy", id), {
        onBefore: () => confirmed,
    });
};
</script>

<template>
    <Head title="Pegawai" />
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li><span class="text-info">Profil Pegawai</span></li>
        </ul>
    </div>
    <MainCard title="Data Pegawai BSN">
        <div class="mt-8">
            <div class="flex justify-between">
                <Link
                    class="btn btn-primary"
                    :href="route('profil_pegawai.create')"
                    >Tambah Pegawai</Link
                >
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
                        <tr v-if="pegawai.data.length < 1">
                            <td colspan="5" class="text-center">
                                Data tidak ditemukan
                            </td>
                        </tr>
                        <tr
                            v-else
                            class="hover"
                            v-for="(item, index) in pegawai.data"
                        >
                            <td>{{ pegawai.from + index }}</td>
                            <td>{{ item.nama }}</td>
                            <td>{{ item.nip }}</td>
                            <td>
                                <span v-if="item.status_dinas === 0">
                                    Aktif
                                </span>
                                <span v-else-if="item.status_dinas === 1">
                                    Tidak Aktif
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <button
                                        class="tooltip btn-primary btn-outline btn-xs tooltip-bottom hover:rounded-lg"
                                        data-tip="Edit"
                                        @click="editPegawai(item.id)"
                                    >
                                        <PencilSquareIcon class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="tooltip btn-info btn-outline btn-xs tooltip-bottom hover:rounded-lg"
                                        data-tip="Detail"
                                        @click="lihatPegawai(item.id)"
                                    >
                                        <EyeIcon class="h-4 w-4" />
                                    </button>
                                    <button
                                        class="tooltip btn-error btn-outline btn-xs tooltip-bottom rounded"
                                        data-tip="Hapus"
                                        @click="hapusPegawai(item.id)"
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 grid gap-2 md:grid-cols-2 md:justify-items-end">
                <ShowingResultTable
                    :from="pegawai.from"
                    :to="pegawai.to"
                    :total="pegawai.total"
                    class="justify-self-start"
                />

                <Pagination
                    :links="pegawai.links"
                    @goToPage="(page) => fetchData({ page })"
                />
            </div>
        </div>
    </MainCard>
</template>
