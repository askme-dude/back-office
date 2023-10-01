<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import { debounce } from "lodash";
import axios from "axios";
import Swal from "sweetalert2";

const addRiwayatDiklat = () => {
    router.get(route("riwayat-diklat.create"));
};
const riwayatDiklat = ref(Object);
onMounted(() => {
    getDataRiwayatDiklat(route("riwayat-diklat.getdata"));
});
const paginate = ref(10);
const cari = ref("");
watch(
    cari,
    debounce((value) => {
        getDataRiwayatDiklat(
            route("riwayat-diklat.getdata") +
                "?cari=" +
                value +
                "&paginate=" +
                paginate.value,
        );
    }, 500),
);
watch(paginate, (value) => {
    getDataRiwayatDiklat(
        route("riwayat-diklat.getdata") +
            "?cari=" +
            cari.value +
            "&paginate=" +
            value,
    );
});
const getDataRiwayatDiklat = async (value) => {
    const result = await axios.get(value);
    riwayatDiklat.value = result.data;
};
const toShow = (id) => {
    getRiwayatDiklatDetail(id);
    modal_riwayat.showModal();
};
const toEdit = (id) => {
    router.get(route("riwayat-diklat.edit", id));
};
const toDelete = (id) => {
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "hapus riwayat diklat pegawai",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Ya",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("riwayat-diklat.destroy", id), {
                onSuccess: (response) => {
                    Swal.fire({
                        title: "Berhasil!",
                        text: response.props.success,
                        icon: "success",
                        confirmButtonText: "OK",
                    });
                    getDataRiwayatDiklat(route("riwayat-diklat.getdata"));
                },
                onError: (errors) => {
                    if (errors.query) {
                        Swal.fire({
                            title: "Gagal!",
                            text: errors.query,
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                },
            });
        }
    });
};
const riwayatDiklatDetail = ref([]);
const getRiwayatDiklatDetail = async (value) => {
    try {
        const result = await axios.get(route("riwayat-diklat.show", value));
        riwayatDiklatDetail.value = result.data.data;
    } catch (e) {
        Swal.fire({
            title: "Error!",
            icon: "error",
            text: e.response.data.message,
            confirmButtonText: "OK",
        });
    }
};
const resetPegawaiRiwayatDetail = () => {
    riwayatDiklatDetail.value = [];
};
</script>

<template>
    <div class="overflow-x-auto">
        <div class="flex justify-between py-4">
            <div class="justify-start">
                <button class="btn btn-primary" @click="addRiwayatDiklat">
                    Tambah
                </button>
            </div>
            <div class="justify-end">
                <input
                    v-model="cari"
                    type="text"
                    placeholder="Cari"
                    class="input input-bordered mr-2 w-auto max-w-xs"
                />
                <select
                    v-model="paginate"
                    class="select select-bordered w-auto max-w-xs"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <table class="table" aria-describedby="Tabel Alamat Pegawai">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Pegawai</th>
                    <th scope="col">Jenis Diklat</th>
                    <th scope="col">Tanggal Mulai</th>
                    <th scope="col">Tanggal Akhir</th>
                    <th scope="col">Penyelenggaraan</th>
                    <th scope="col">No Sertifikat</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="hover"
                    v-for="(riwayat, index) in riwayatDiklat.data"
                >
                    <th scope="col">{{ index + 1 }}</th>
                    <td>{{ riwayat.nama_lengkap }}</td>
                    <td>{{ riwayat.nama }}</td>
                    <td>{{ riwayat.tanggal_mulai }}</td>
                    <td>{{ riwayat.tanggal_akhir }}</td>
                    <td>{{ riwayat.penyelenggaran }}</td>
                    <td>{{ riwayat.no_sertifikat }}</td>
                    <td>
                        <div class="dropdown dropdown-left">
                            <div class="join">
                                <button
                                    class="tooltip btn-primary btn-outline join-item btn-xs tooltip-bottom"
                                    data-tip="Edit"
                                    @click="toEdit(riwayat.id)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                        />
                                    </svg>
                                </button>
                                <button
                                    class="tooltip btn-info btn-outline join-item btn-xs tooltip-bottom"
                                    data-tip="Detail"
                                    @click="toShow(riwayat.id)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    class="tooltip btn-error btn-outline join-item btn-xs tooltip-bottom"
                                    data-tip="Hapus"
                                    @click="toDelete(riwayat.id)"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="join flex justify-end">
            <Component
                :is="link.url ? 'a' : 'span'"
                v-for="link in riwayatDiklat.links"
                @click="
                    getDataRiwayatDiklat(
                        link.url + '&paginate=' + paginate + '&cari=' + cari,
                    )
                "
                v-html="link.label"
                class="btn join-item btn-xs"
                :class="{
                    'btn-disabled': !link.url,
                    'btn-active': link.active,
                }"
            />
        </div>
    </div>
    <dialog id="modal_riwayat" class="modal">
        <form method="dialog" class="modal-box">
            <div class="overflow-x-auto">
                <table class="w-11/12">
                    <caption class="mb-4 text-lg font-bold">
                        Detail Riwayat
                    </caption>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tr>
                        <td class="">Nama Pegawai</td>
                        <td
                            v-if="riwayatDiklatDetail"
                            v-html="riwayatDiklatDetail.nama_lengkap"
                        ></td>
                    </tr>
                    <tr>
                        <td>Jenis Diklat</td>
                        <td
                            v-if="riwayatDiklatDetail"
                            v-html="riwayatDiklatDetail.nama_diklat"
                        ></td>
                    </tr>
                    <tr>
                        <td>Tanggal Mulai</td>
                        <td v-html="riwayatDiklatDetail.tanggal_mulai"></td>
                    </tr>
                    <tr>
                        <td>Tanggal Akhir</td>
                        <td v-html="riwayatDiklatDetail.tanggal_akhir"></td>
                    </tr>
                    <tr>
                        <td>Jam Pelajaran</td>
                        <td v-html="riwayatDiklatDetail.jam_pelajaran"></td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td v-html="riwayatDiklatDetail.lokasi"></td>
                    </tr>
                    <tr>
                        <td>Penyelenggaraan</td>
                        <td v-html="riwayatDiklatDetail.penyelenggaran"></td>
                    </tr>
                    <tr>
                        <td>No Sertifikat</td>
                        <td v-html="riwayatDiklatDetail.no_sertifikat"></td>
                    </tr>
                    <tr>
                        <td>Tanggal Sertifikat</td>
                        <td
                            v-html="riwayatDiklatDetail.tanggal_sertifikat"
                        ></td>
                    </tr>
                    <tr>
                        <td>File Sertifikat</td>
                        <td>
                            <a
                                :href="
                                    '//' + riwayatDiklatDetail.media_sertifikat
                                "
                                target="_blank"
                                class="tooltip tooltip-right"
                                data-tip="Unduh"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                    />
                                </svg>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-action">
                <button
                    class="btn btn-primary btn-sm"
                    @click="resetPegawaiRiwayatDetail"
                >
                    Tutup
                </button>
            </div>
        </form>
    </dialog>
</template>

<style scoped></style>
