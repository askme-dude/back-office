<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import MainCard from "@/Components/MainCard.vue";
import { useNoPhotoUrl } from "@/Composables/helpers.ts";

defineProps({
    pegawai: Object,
    data: Object,
    media_foto_pegawai: String,
});

const noPhotoUrl = useNoPhotoUrl();

// const perPage = ref(props.pegawai.per_page);

// const cari = ref(props.filter.cari);

// const getPerPage = () => {
//     router.get(
//         route("profil_pegawai.index"),
//         { perPage: perPage.value, cari: cari.value },
//         {
//             preserveState: true,
//             preserveScroll: true,
//             replace: true,
//         },
//     );
// };

// watch(
//     cari,
//     debounce((value) => {
//         router.get(
//             route("profil_pegawai.index"),
//             { perPage: perPage.value, cari: value },
//             {
//                 preserveState: true,
//                 preserveScroll: true,
//                 replace: true,
//             },
//         );
//     }, 500),
// );

// const tambahRiwayatJabatanPegawai = () => {
//     router.get(route("riwayat_jabatan_pegawai.create"));
// };
</script>

<template>
    <div>
        <Head title="Lihat Pegawai" />
        <Breadcrumb
            :lists="[
                { label: 'Beranda', url: route('dashboard') },
                { label: 'Pegawai', url: route('profil_pegawai.index') },
                {
                    label: 'Data Pegawai',
                    url: route('profil_pegawai.show', pegawai.id),
                },
                { label: 'Riwayat Jabatan' },
            ]"
        />
        <MainCard>
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-5">
                    <table class="text-left">
                        <tr>
                            <th class="align-top">Nama Lengkap</th>
                            <td class="px-2 align-top">:</td>
                            <td class="align-top">{{ pegawai.nama }}</td>
                        </tr>
                        <tr>
                            <th class="align-top">Nomor Induk Pegawai</th>
                            <td class="px-2 align-top">:</td>
                            <td class="align-top">{{ pegawai.nip }}</td>
                        </tr>
                        <tr>
                            <th class="align-top">Riwayat Jabatan</th>
                            <td class="px-2 align-top">:</td>
                            <td class="align-top">
                                <div class="overflow-x-auto">
                                    <table class="table">
                                        <!-- head -->
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Jabatan</th>
                                                <th>Jenis Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in data">
                                                <td>
                                                    {{ index + 1 }}
                                                </td>
                                                <td>
                                                    {{
                                                        item.jabatan_unit_kerja_id
                                                    }}
                                                </td>
                                                <td>
                                                    Quality Control Specialist
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{ data }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-span-1">
                    <img
                        :src="media_foto_pegawai || noPhotoUrl"
                        class="mb-4 rounded-md border-2"
                        alt=""
                    />
                </div>
            </div>
        </MainCard>
    </div>
    <!-- <div>
        <Head title="Riwayat Jabatan" />
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a>Beranda</a></li>
                <li>Pegawai</li>
                <li><span class="text-info">Riwayat Jabatan Pegawai</span></li>
            </ul>
        </div>
        <MainCard>
            <div class="overflow-x-auto">
                <div class="flex justify-between py-4">
                    <div class="justify-start">
                        <button
                            class="btn btn-primary"
                            @click="tambahRiwayatJabatanPegawai"
                        >
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
                            v-model="perPage"
                            @change="getPerPage"
                            class="select select-bordered w-auto max-w-xs"
                        >
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                <table class="table" aria-describedby="Tabel Profil Pegawai">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="pegawai.data.length != 0"
                            class="hover"
                            v-for="(item, index) in pegawai.data"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>
                                {{ item.nama_depan + " " + item.nama_belakang }}
                            </td>
                            <td>{{ item.nip }}</td>
                            <td v-if="item.status_dinas == 0">Aktif</td>
                            <td v-if="item.status_dinas == 1">Tidak Aktif</td>
                            <td>
                                <div class="flex justify-center">
                                    <button
                                        class="tooltip btn-primary btn-outline btn-xs tooltip-bottom hover:rounded-lg"
                                        data-tip="Edit"
                                        @click="editPegawai(item.id)"
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
                                        class="tooltip btn-info btn-outline btn-xs tooltip-bottom hover:rounded-lg"
                                        data-tip="Detail"
                                        @click="lihatPegawai(item.id)"
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
                                        class="tooltip btn-error btn-outline btn-xs tooltip-bottom hover:rounded-lg"
                                        data-tip="Hapus"
                                        @click="hapusPegawai(item.id)"
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
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="5" class="text-center">
                                data tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 flex justify-between">
                    <div>
                        {{ pegawai.data.length }} dari
                        {{ pegawai.total }} pegawai
                    </div>
                    <div>
                        <template v-for="link in pegawai.links">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="btn join-item btn-xs"
                                :class="{
                                    'btn-disabled': !link.url,
                                    'btn-active': link.active,
                                }"
                            />

                            <span v-else v-html="link.label"></span>
                        </template>
                    </div>
                </div>
            </div>
        </MainCard>
    </div> -->
</template>
