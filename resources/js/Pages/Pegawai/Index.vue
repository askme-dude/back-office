<script setup>
import { Link, router } from "@inertiajs/vue3";
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
import { ref } from "vue";
import MainCard from "@/Components/MainCard.vue";
import SearchInputColumn from "@/Components/SearchInputColumn.vue";
import Pagination from "@/Components/Pagination.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import { useQuery } from "@/Composables/tablequery.ts";
import { useConfirm } from "@/Composables/sweetalert.ts";
import PerPageOption from "@/Components/PerPageOption.vue";
import HeadColumn from "@/Components/HeadColumn.vue";

const props = defineProps({
    pegawai: Object,
});

const columns = [
    { label: "Nama", column: "nama" },
    { label: "NIP", column: "nip" },
    { label: "Status", column: "status_dinas" },
];

const filterBy = ref({ label: "Nama", column: "nama" });
const sortBy = ref(null);
const isLoading = ref(false);
const perPage = ref(15);
const keyword = ref("");
const query = useQuery({ keyword, filterBy, sortBy, perPage });

const fetchData = (params = {}) => {
    router.get(
        route("pegawai.index", {
            _query: { ...query.value, ...params },
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
    router.get(route("pegawai.show", id));
};

const editPegawai = (id) => {
    router.get(route("pegawai.edit", id));
};

const hapusPegawai = async (id) => {
    const confirmed = await useConfirm({ text: "Hapus data pegawai" });

    router.delete(route("pegawai.destroy", id), {
        onBefore: () => confirmed,
    });
};
</script>

<template>
    <Head title="Pegawai" />

    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>Beranda</a></li>
            <li><span class="text-info">Pegawai</span></li>
        </ul>
    </div>

    <MainCard title="Daftar Pegawai">
        <div class="mt-8 grid gap-2 md:flex md:justify-between">
            <div>
                <Link
                    :href="route('pegawai.create')"
                    class="btn btn-primary btn-outline"
                >
                    Rekam Pegawai Baru
                </Link>
            </div>
            <div class="flex gap-2">
                <SearchInputColumn
                    :options="columns"
                    v-model:keyword="keyword"
                    v-model:selected="filterBy"
                    :search="() => fetchData()"
                />
                <PerPageOption v-model="perPage" @change="() => fetchData()" />
            </div>
        </div>
        <div class="mt-4">
            <table class="table" aria-describedby="Tabel Profil Pegawai">
                <thead>
                    <tr>
                        <th scope="col">No</th>

                        <HeadColumn
                            v-for="col in columns"
                            :key="col.label"
                            v-model="sortBy"
                            :content="col"
                            @click="() => fetchData()"
                        />
                        <th scope="col" class="w-16">Aksi</th>
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
                            <span v-if="item.status_dinas === 0"> Aktif </span>
                            <span v-else-if="item.status_dinas === 1">
                                Tidak Aktif
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center">
                                <button
                                    class="btn-primary btn-outline tooltip btn-xs tooltip-bottom hover:rounded-lg"
                                    data-tip="Edit"
                                    @click="editPegawai(item.id)"
                                >
                                    <PencilSquareIcon class="h-4 w-4" />
                                </button>
                                <button
                                    class="btn-info btn-outline tooltip btn-xs tooltip-bottom hover:rounded-lg"
                                    data-tip="Detail"
                                    @click="lihatPegawai(item.id)"
                                >
                                    <EyeIcon class="h-4 w-4" />
                                </button>
                                <button
                                    class="btn-error btn-outline tooltip btn-xs tooltip-bottom rounded"
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

        <div class="mt-4 grid gap-2 md:flex md:justify-between">
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
    </MainCard>
</template>
