<script setup>
import MainCard from "@/Components/MainCard.vue";
import { Link } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { useNoPhotoUrl } from "@/Composables/helpers.ts";

defineProps({
    pegawai: Object,
    media_foto_pegawai: String,
    media_kartu_pegawai: String,
});

const noPhotoUrl = useNoPhotoUrl();

const tanggalFormat = (dateString) => {
    const date = new Date(dateString);

    return date.toLocaleDateString("ID");
};
</script>

<template>
    <div>
        <Head title="Lihat Pegawai" />
        <Breadcrumb
            :lists="[
                { label: 'Beranda', url: route('dashboard') },
                { label: 'Pegawai', url: route('profil_pegawai.index') },
                { label: 'Data Pegawai' },
            ]"
        />
        <MainCard>
            <div class="grid grid-cols-6 gap-4">
                <table class="col-span-4 text-left">
                    <tr>
                        <th class="align-top">Nomor Induk Kependudukan</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.nik }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Nomor Induk Pegawai</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.nip }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Nama Lengkap</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.nama }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Jenis Kelamin</th>
                        <td class="px-2 align-top">:</td>
                        <td
                            class="align-top"
                            v-if="pegawai.jenis_kelamin == 'L'"
                        >
                            Laki-laki
                        </td>
                        <td
                            class="align-top"
                            v-if="pegawai.jenis_kelamin == 'P'"
                        >
                            Perempuan
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Agama</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.agama.nama }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Golongan Darah</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.golongan_darah }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Status Nikah</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">
                            {{ pegawai.jenis_kawin.nama }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Tempat, Tanggal Lahir</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">
                            {{ pegawai.tempat_lahir }},
                            {{ tanggalFormat(pegawai.tanggal_lahir) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Email Kantor</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.email_kantor }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Email Pribadi</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.email_pribadi }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">No Telepon</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">{{ pegawai.no_telp }}</td>
                    </tr>
                    <tr>
                        <th class="align-top">Jenis Pegawai</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">
                            {{ pegawai.jenis_pegawai.nama }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Status Pegawai</th>
                        <td class="px-2 align-top">:</td>
                        <td class="align-top">
                            {{ pegawai.status_pegawai.nama }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Status Dinas</th>
                        <td class="px-2 align-top">:</td>
                        <td v-if="pegawai.status_dinas == 0" class="align-top">
                            Aktif
                        </td>
                        <td v-if="pegawai.status_dinas == 1" class="align-top">
                            Tidak Aktif
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Tanggal Berhenti</th>
                        <td class="px-2 align-top">:</td>
                        <td
                            v-if="pegawai.tanggal_berhenti != null"
                            class="align-top"
                        >
                            {{ tanggalFormat(pegawai.tanggal_berhenti) }}
                        </td>
                        <td
                            v-if="pegawai.tanggal_berhenti == null"
                            class="align-top"
                        >
                            -
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Tanggal Wafat</th>
                        <td class="px-2 align-top">:</td>
                        <td
                            v-if="pegawai.tanggal_wafat != null"
                            class="align-top"
                        >
                            {{ tanggalFormat(pegawai.tanggal_wafat) }}
                        </td>
                        <td
                            v-if="pegawai.tanggal_wafat == null"
                            class="align-top"
                        >
                            -
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">No BPJS</th>
                        <td class="px-2 align-top">:</td>
                        <td v-if="pegawai.no_bpjs != null" class="align-top">
                            {{ pegawai.no_bpjs }}
                        </td>
                        <td v-if="pegawai.no_bpjs == null" class="align-top">
                            -
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">No Taspen</th>
                        <td class="px-2 align-top">:</td>
                        <td v-if="pegawai.no_taspen != null" class="align-top">
                            {{ pegawai.no_taspen }}
                        </td>
                        <td v-if="pegawai.no_taspen == null" class="align-top">
                            -
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">NPWP</th>
                        <td class="px-2 align-top">:</td>
                        <td v-if="pegawai.npwp != null" class="align-top">
                            {{ pegawai.npwp }}
                        </td>
                        <td v-if="pegawai.npwp == null" class="align-top">-</td>
                    </tr>
                    <tr>
                        <th class="align-top">No Fingerprint</th>
                        <td class="px-2 align-top">:</td>
                        <td v-if="pegawai.no_enroll != null" class="align-top">
                            {{ pegawai.no_enroll }}
                        </td>
                        <td v-if="pegawai.no_enroll == null" class="align-top">
                            -
                        </td>
                    </tr>
                    <tr>
                        <th class="align-top">Kartu Pegawai</th>
                        <td class="px-2 align-top">:</td>
                        <td
                            v-if="pegawai.no_kartu_pegawai != null"
                            class="align-top"
                        >
                            {{ pegawai.no_kartu_pegawai }}
                        </td>
                        <td
                            v-if="pegawai.no_kartu_pegawai == null"
                            class="align-top"
                        >
                            -
                        </td>
                    </tr>
                </table>
                <div class="col-span-2">
                    <img
                        :src="media_foto_pegawai || noPhotoUrl"
                        class="mb-4 rounded-md border-2"
                        alt=""
                    />
                    <img
                        :src="media_kartu_pegawai || noPhotoUrl"
                        class="rounded-md border-2"
                        alt=""
                    />
                </div>
            </div>
            <div class="mt-4 grid justify-items-end">
                <Link
                    class="rounded-md bg-yellow-600 px-4 py-2 text-white hover:bg-yellow-500"
                    :href="route('profil_pegawai.edit', pegawai.id)"
                >
                    Edit
                </Link>
            </div>
        </MainCard>
    </div>
</template>
