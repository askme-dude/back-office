<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import FormBody from "@/Pages/Pegawai/PegawaiRiwayatDiklat/components/FormBody.vue";
import { useToast } from "@/Composables/sweetalert.ts";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps(["title"]);

const form = useForm({
    pegawai_id: null,
    nama: null,
    jenis_diklat_id: null,
    tanggal_mulai: null,
    tanggal_akhir: null,
    jam_pelajaran: null,
    lokasi: null,
    penyelenggara: null,
    no_sertifikat: null,
    tanggal_sertifikat: null,
    media_sertifikat: null,
});
const submit = () => {
    form.post(route("riwayat-diklat.store"), {
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
