<script setup>
import MainCard from "@/Components/MainCard.vue";
import { useForm } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { useToast } from "@/Composables/sweetalert.ts";
import FormBody from "@/Pages/Pegawai/PegawaiAlamat/components/FormBody.vue";

const form = useForm({
    pegawai_id: null,
    tipe_alamat: null,
    propinsi_id: null,
    kota_id: null,
    kecamatan_id: null,
    desa_id: null,
    kode_pos: null,
    alamat: null,
});

const submit = () => {
    form.post(route("alamat.store"), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onError: (errors) =>
            useToast({ icon: "error", text: Object.values(errors)[0] }),
    });
};
</script>

<template>
    <Head title="Rekam Alamat Baru" />
    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Pegawai', url: route('pegawai.index') },
            { label: 'Alamat', url: route('alamat.index') },
            { label: 'Tambah Data' },
        ]"
    />
    <MainCard title="Rekam Alamat Baru">
        <form @submit.prevent="submit" class="mx-auto max-w-7xl">
            <FormBody
                v-model:pegawai_id="form.pegawai_id"
                v-model:tipe_alamat="form.tipe_alamat"
                v-model:propinsi_id="form.propinsi_id"
                v-model:kota_id="form.kota_id"
                v-model:kecamatan_id="form.kecamatan_id"
                v-model:desa_id="form.desa_id"
                v-model:kode_pos="form.kode_pos"
                v-model:alamat="form.alamat"
            />
            <div class="mt-4 flex justify-end gap-2">
                <button
                    class="btn btn-neutral btn-outline"
                    onclick="window.history.back()"
                >
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </MainCard>
</template>
