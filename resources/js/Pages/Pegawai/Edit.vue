<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import MainCard from "@/Components/MainCard.vue";
import FormBody from "@/Pages/Pegawai/components/FormBody.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { useConfirm } from "@/Composables/sweetalert.ts";

const props = defineProps({
    pegawai: Object,
    media_foto_pegawai: String,
    media_kartu_pegawai: String,
});

const form = useForm({
    nik: props.pegawai.nik,
    nip: props.pegawai.nip,
    nama: props.pegawai.nama,
    jenis_kelamin: props.pegawai.jenis_kelamin,
    agama_id: props.pegawai.agama_id,
    golongan_darah: props.pegawai.golongan_darah,
    jenis_kawin_id: props.pegawai.jenis_kawin_id,
    tempat_lahir: props.pegawai.tempat_lahir,
    tanggal_lahir: props.pegawai.tanggal_lahir,
    email_kantor: props.pegawai.email_kantor,
    email_pribadi: props.pegawai.email_pribadi,
    no_telp: props.pegawai.no_telp,
    jenis_pegawai_id: props.pegawai.jenis_pegawai_id,
    status_pegawai_id: props.pegawai.status_pegawai_id,
    status_dinas: props.pegawai.status_dinas,
    no_kartu_pegawai: props.pegawai.no_kartu_pegawai,
    tanggal_berhenti: props.pegawai.tanggal_berhenti,
    tanggal_wafat: props.pegawai.tanggal_wafat,
    no_bpjs: props.pegawai.no_bpjs,
    no_taspen: props.pegawai.no_taspen,
    npwp: props.pegawai.npwp,
    no_enroll: props.pegawai.no_enroll,
    media_foto_pegawai: "",
    media_kartu_pegawai: "",
});

const isLoading = ref(false);

const updatePegawai = async () => {
    const confirmed = await useConfirm({ text: "Simpan data pegawai" });

    form.transform((data) => ({ ...data, _method: "put" })).post(
        route("pegawai.update", props.pegawai.id),
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onBefore: () => confirmed,
            onStart: () => (isLoading.value = true),
            onFinish: () => (isLoading.value = true),
        },
    );
};
</script>

<template>
    <div>
        <Head title="Edit Pegawai" />
        <Breadcrumb
            :lists="[
                { label: 'Beranda', url: route('dashboard') },
                { label: 'Pegawai', url: route('pegawai.index') },
                { label: 'Edit Pegawai' },
            ]"
        />
        <MainCard>
            <form @submit.prevent="updatePegawai" class="mx-auto max-w-7xl">
                <FormBody
                    :show-photo="true"
                    v-model:nik="form.nik"
                    v-model:nip="form.nip"
                    v-model:nama="form.nama"
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
                <div class="flex justify-between pt-4">
                    <button
                        type="reset"
                        onclick="window.history.back()"
                        class="btn btn-outline hover:btn-error"
                    >
                        Batal
                    </button>
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
