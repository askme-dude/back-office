<script setup>
import MainCard from "@/Components/MainCard.vue";
import FormBody from "@/Pages/Pegawai/PegawaiRiwayatPenghargaan/components/FormBody.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps(["riwayat"]);

const form = useForm({
    pegawai_id: props.riwayat.pegawai_id,
    penghargaan_id: props.riwayat.penghargaan_id,
    no_sk: props.riwayat.no_sk,
    tanggal_sk: props.riwayat.tanggal_sk,
    tahun: props.riwayat.tahun,
    media_sk: null,
});

const submit = () => {
    form.transform((data) => ({ ...data, _method: "put" })).post(
        route("riwayat-penghargaan.update", props.riwayat),
    );
};
</script>

<template>
    <Head title="Edit Riwayat Penghargaan" />

    <MainCard title="Edit Riwayat Penghargaan">
        <form class="mt-8" @submit.prevent="submit">
            <FormBody
                v-model:media_sk="form.media_sk"
                v-model:tahun="form.tahun"
                v-model:tanggal_sk="form.tanggal_sk"
                v-model:no_sk="form.no_sk"
                v-model:penghargaan_id="form.penghargaan_id"
                v-model:pegawai_id="form.pegawai_id"
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
