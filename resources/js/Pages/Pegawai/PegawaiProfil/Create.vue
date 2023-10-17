<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

import MainCard from "@/Components/MainCard.vue";
import { useConfirm } from "@/Composables/sweetalert.ts";
import FormBody from "@/Pages/Pegawai/PegawaiProfil/components/FormBody.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

// defineProps({
//     agama: Array,
//     statusNikah: Array,
//     jenisPegawai: Array,
//     statusPegawai: Array,
// });

const form = useForm({
    nik: "",
    nip: "",
    nama_depan: "",
    nama_belakang: "",
    jenis_kelamin: "",
    agama_id: "",
    golongan_darah: "",
    jenis_kawin_id: "",
    tempat_lahir: "",
    tanggal_lahir: "",
    email_kantor: "",
    email_pribadi: "",
    no_telp: "",
    jenis_pegawai_id: "",
    status_pegawai_id: "",
    status_dinas: "",
    no_kartu_pegawai: "",
    tanggal_berhenti: "",
    tanggal_wafat: "",
    no_bpjs: "",
    no_taspen: "",
    npwp: "",
    no_enroll: "",
    media_foto_pegawai: null,
    media_kartu_pegawai: null,
});

const isLoading = ref(false);

const simpanPegawai = async () => {
    const confirmed = await useConfirm({ text: "Simpan data pegawai baru" });

    form.post(route("profil_pegawai.store"), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onBefore: () => confirmed,
        onStart: () => (isLoading.value = true),
        onFinish: () => (isLoading.value = false),
    });
};
</script>

<template>
    <div>
        <Head title="Tambah Pegawai" />
        <Breadcrumb
            :lists="[
                { label: 'Beranda', url: route('dashboard') },
                { label: 'Pegawai', url: route('profil_pegawai.index') },
                { label: 'Rekam Pegawai Baru' },
            ]"
        />
        <MainCard>
            <form @submit.prevent="simpanPegawai">
                <FormBody
                    v-model:nik="form.nik"
                    v-model:nip="form.nip"
                    v-model:nama_depan="form.nama_depan"
                    v-model:nama_belakang="form.nama_belakang"
                    v-model:jenis_kelamin="form.jenis_kelamin"
                    v-model:agama_id="form.agama_id"
                    v-model:golongan_darah="form.golongan_darah"
                    v-model:jenis_kawin_id="form.jenis_kawin_id"
                    v-model:tempat_lahir="form.tempat_lahir"
                    v-model:tanggal_lahir="form.tanggal_lahir"
                    v-model:email_kantor="form.email_kantor"
                    v-model:email_pribadi="form.email_pribadi"
                    v-model:no_telp="form.no_telp"
                    v-model:jenis_pegawai_id="form.jenis_pegawai_id"
                    v-model:status_pegawai_id="form.status_pegawai_id"
                    v-model:status_dinas="form.status_dinas"
                    v-model:no_kartu_pegawai="form.no_kartu_pegawai"
                    v-model:tanggal_berhenti="form.tanggal_berhenti"
                    v-model:tanggal_wafat="form.tanggal_wafat"
                    v-model:no_bpjs="form.no_bpjs"
                    v-model:no_taspen="form.no_taspen"
                    v-model:npwp="form.npwp"
                    v-model:no_enroll="form.no_enroll"
                    v-model:media_foto_pegawai="form.media_foto_pegawai"
                    v-model:media_kartu_pegawai="form.media_kartu_pegawai"
                />
                <div class="mt-4 flex justify-end gap-2">
                    <Link
                        type="reset"
                        :href="route('profil_pegawai.index')"
                        class="btn btn-neutral btn-outline"
                    >
                        Batal
                    </Link>
                    <button
                        :disabled="isLoading"
                        type="submit"
                        class="btn btn-primary"
                    >
                        <div v-if="isLoading">
                            <span class="loading loading-spinner loading-sm">
                            </span>
                        </div>
                        Simpan
                    </button>
                </div>
            </form>
        </MainCard>
    </div>
</template>
