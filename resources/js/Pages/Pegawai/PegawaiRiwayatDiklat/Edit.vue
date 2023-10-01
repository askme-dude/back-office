<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { useToast } from "@/Composables/sweetalert.ts";
import FormBody from "@/Pages/Pegawai/PegawaiRiwayatDiklat/components/FormBody.vue";

const props = defineProps([
    "title",
    "pegawai",
    "jenis_diklat",
    "riwayatDiklat",
]);

const form = useForm({
    pegawai_id: props.riwayatDiklat.pegawai_id,
    nama: props.riwayatDiklat.nama,
    jenis_diklat_id: props.riwayatDiklat.jenis_diklat_id,
    tanggal_mulai: props.riwayatDiklat.tanggal_mulai,
    tanggal_akhir: props.riwayatDiklat.tanggal_akhir,
    jam_pelajaran: props.riwayatDiklat.jam_pelajaran,
    lokasi: props.riwayatDiklat.lokasi,
    penyelenggara: props.riwayatDiklat.penyelenggara,
    no_sertifikat: props.riwayatDiklat.no_sertifikat,
    tanggal_sertifikat: props.riwayatDiklat.tanggal_sertifikat,
    media_sertifikat: null,
});
const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: "put",
    })).post(route("riwayat-diklat.update", props.riwayatDiklat), {
        onError: (errors) =>
            useToast({ icon: "error", text: Object.values(errors)[0] }),
    });
};
</script>

<template>
    <Head :title="title" />

    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Pegawai', url: route('pegawai.index') },
            { label: 'Diklat', url: route('riwayat-diklat.index') },
            { label: title },
        ]"
    />

    <MainCard :title="title">
        <form class="mx-auto mt-8 lg:max-w-7xl" @submit.prevent="submit">
            <FormBody
                v-model:pegawai_id="form.pegawai_id"
                v-model:nama="form.nama"
                v-model:jenis_diklat_id="form.jenis_diklat_id"
                v-model:tanggal_mulai="form.tanggal_mulai"
                v-model:tanggal_akhir="form.tanggal_akhir"
                v-model:jam_pelajaran="form.jam_pelajaran"
                v-model:lokasi="form.lokasi"
                v-model:penyelenggara="form.penyelenggara"
                v-model:no_sertifikat="form.no_sertifikat"
                v-model:tanggal_sertifikat="form.tanggal_sertifikat"
                v-model:media_sertifikat="form.media_sertifikat"
            />
            <div class="mt-4 flex justify-end gap-2">
                <button
                    type="reset"
                    class="btn btn-neutral btn-outline"
                    @click="router.get(route('riwayat-diklat.index'))"
                >
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </MainCard>
</template>
