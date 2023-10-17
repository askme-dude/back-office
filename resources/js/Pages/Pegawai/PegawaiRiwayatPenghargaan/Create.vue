<script setup>
import MainCard from "@/Components/MainCard.vue";
import FormBody from "@/Pages/Pegawai/PegawaiRiwayatPenghargaan/components/FormBody.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    pegawai_id: null,
    penghargaan_id: null,
    no_sk: null,
    tanggal_sk: null,
    tahun: null,
    media_sk: null,
});

const submit = () => {
    form.post(route("riwayat-penghargaan.store"));
};
</script>

<template>
    <Head title="Buat Riwayat Penghargaan" />

    <MainCard title="Buat Riwayat Penghargaan">
        <form class="mt-8" @submit.prevent="submit">
            <FormBody
                v-model:pegawai_id="form.pegawai_id"
                v-model:penghargaan_id="form.penghargaan_id"
                v-model:no_sk="form.no_sk"
                v-model:tanggal_sk="form.tanggal_sk"
                v-model:tahun="form.tahun"
                v-model:media_sk="form.media_sk"
            />
            <div class="mt-4 flex justify-end gap-2">
                <Link
                    class="btn btn-neutral btn-outline"
                    :href="route('riwayat-penghargaan.index')"
                    >Batal
                </Link>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </MainCard>
</template>
