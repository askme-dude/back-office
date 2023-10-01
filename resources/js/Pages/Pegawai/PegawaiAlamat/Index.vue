<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { useConfirm } from "@/Composables/sweetalert.ts";

const props = defineProps(["pegawaiAlamat"]);

const perPage = ref(15);
const keyword = ref("");
const selectedAlamat = ref(null);
const tambahAlamat = () => {
    router.get(route("alamat.create"));
};
const toEdit = (id) => {
    router.get(route("alamat.edit", id));
};

const query = computed(() => {
    return {
        ...(keyword.value && {
            filter: {
                keyword: keyword.value,
            },
        }),
        ...(perPage.value && { per_page: perPage.value }),
    };
});

const fetchData = (params = {}) => {
    router.get(
        route("alamat.index", {
            _query: { ...query.value, ...params },
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
const toDelete = async (id) => {
    const confirmed = await useConfirm({ text: "Hapus data alamat" });

    router.delete(route("alamat.destroy", id), {
        onBefore: () => confirmed,
    });
};

const showDetail = (alamat) => {
    selectedAlamat.value = alamat;
    modal_alamat.showModal();
};
</script>

<template>
    <Head title="Alamat Pegawai" />
    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Pegawai', url: route('pegawai.index') },
            { label: 'Alamat' },
        ]"
    />
    <MainCard title="Daftar Alamat Pegawai">
        <div class="flex justify-between py-4">
            <div>
                <button
                    class="btn btn-primary btn-outline"
                    @click="tambahAlamat"
                >
                    Rekam Alamat Baru
                </button>
            </div>
            <div class="flex gap-2">
                <SearchInput v-model="keyword" :search="() => fetchData()" />
                <PerPageOption v-model="perPage" @change="() => fetchData()" />
            </div>
        </div>
        <table class="table" aria-describedby="Tabel Alamat Pegawai">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tipe</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Propinsi</th>
                    <th scope="col">Kota/Kabupaten</th>
                    <th scope="col">Kecamatan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="hover"
                    v-for="(row, i) in pegawaiAlamat.data"
                    :key="row.id"
                >
                    <td>{{ pegawaiAlamat.from + i }}</td>
                    <td>{{ row.pegawai.nama }}</td>
                    <td>{{ row.tipe_alamat }}</td>
                    <td>{{ row.alamat }}</td>
                    <td>{{ row.propinsi.nama }}</td>
                    <td>{{ row.kota.nama }}</td>
                    <td>{{ row.kecamatan.nama }}</td>
                    <td>
                        <div class="dropdown-left dropdown">
                            <label tabindex="0" class="btn btn-xs m-1">
                                Aksi
                            </label>
                            <ul
                                tabindex="0"
                                class="w-30 menu dropdown-content rounded-box z-[1] bg-base-100 p-2 shadow"
                            >
                                <li>
                                    <span @click="toEdit(row.id)"> Edit </span>
                                </li>
                                <li>
                                    <span @click="showDetail(row)">
                                        Detail
                                    </span>
                                </li>
                                <li>
                                    <span @click="toDelete(row.id)">
                                        Hapus
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 flex justify-between">
            <ShowingResultTable
                :from="pegawaiAlamat.from"
                :to="pegawaiAlamat.to"
                :total="pegawaiAlamat.total"
            />
            <Pagination
                :links="pegawaiAlamat.links"
                @goToPage="(page) => fetchData({ page })"
            />
        </div>
    </MainCard>
    <!-- Open the modal using ID.showModal() method -->
    <dialog id="modal_alamat" class="modal">
        <form method="dialog" class="modal-box">
            <h1 class="mb-4 text-center text-lg font-bold">Detail Pegawai</h1>
            <div v-if="selectedAlamat" class="flex flex-col gap-1">
                <div class="flex">
                    <div class="w-1/3">Nama Pegawai</div>
                    <div class="w-2/3">{{ selectedAlamat.pegawai.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Alamat</div>
                    <div class="w-2/3">{{ selectedAlamat.alamat }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Tipe Alamat</div>
                    <div class="w-2/3">{{ selectedAlamat.tipe_alamat }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Propinsi</div>
                    <div class="w-2/3">{{ selectedAlamat.propinsi.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Kota/Kabupaten</div>
                    <div class="w-2/3">{{ selectedAlamat.kota.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Kecamatan</div>
                    <div class="w-2/3">{{ selectedAlamat.kecamatan.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Desa</div>
                    <div class="w-2/3">{{ selectedAlamat.desa.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Kode Pos</div>
                    <div class="w-2/3">{{ selectedAlamat.kode_pos }}</div>
                </div>
            </div>
            <div class="modal-action">
                <button class="btn btn-neutral btn-outline btn-sm">
                    Tutup
                </button>
            </div>
        </form>
    </dialog>
</template>
