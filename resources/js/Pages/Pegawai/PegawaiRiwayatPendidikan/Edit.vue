<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

const props = defineProps({
    title: "",
    pegawai: "",
    pendidikan: "",
    propinsi: "",
    riwayatPendidikanDetail: "",
    kota: "",
});
const form = useForm({
    id: props.riwayatPendidikanDetail.id,
    pegawai_id: props.riwayatPendidikanDetail.pegawai_id,
    pendidikan_id: props.riwayatPendidikanDetail.pendidikan_id,
    nama_instansi: props.riwayatPendidikanDetail.nama_instansi,
    propinsi_id: props.riwayatPendidikanDetail.propinsi_id,
    kota_id: props.riwayatPendidikanDetail.kota_id,
    row: props.riwayatPendidikanDetail.row,
    no_ijazah: props.riwayatPendidikanDetail.no_ijazah,
    tanggal_ijazah: props.riwayatPendidikanDetail.tanggal_ijazah,
    kode_gelar_depan: props.riwayatPendidikanDetail.kode_gelar_depan,
    kode_gelar_belakang: props.riwayatPendidikanDetail.kode_gelar_belakang,
    media_ijazah: "",
});
const saveRiwayatPendidikan = () => {
    form.transform((data) => ({
        ...data,
        _method: "put",
    })).post(
        route("riwayat-pendidikan.update", props.riwayatPendidikanDetail.id),
        {
            onSuccess: (response) => {
                Swal.fire({
                    title: "Tersimpan!",
                    text: response.props.success,
                    icon: "success",
                    confirmButtonText: "OK",
                });
                router.get(route("riwayat-pendidikan.index"));
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
        },
    );
};
const selectedPegawai = computed({
    get() {
        return props.pegawai.find((peg) => peg.id === form.pegawai_id);
    },
    set(pegawai) {
        form.pegawai_id = pegawai.id;
    },
});
const selectedPendidikan = computed({
    get() {
        return props.pendidikan.find((pend) => pend.id === form.pendidikan_id);
    },
    set(pendidikan) {
        form.pendidikan_id = pendidikan.id;
    },
});
const selectedPropinsi = computed({
    get() {
        return props.propinsi.find((prop) => prop.id === form.propinsi_id);
    },
    set(propinsi) {
        form.propinsi_id = propinsi.id;
    },
});
const kota = ref(props.kota);
const getKota = async (value) => {
    const result = await axios.post(route("kota.getdata"), {
        propinsi_id: value,
    });
    kota.value = result.data;
};
watch(
    () => form.propinsi_id,
    (value) => {
        getKota(value);
    },
);
const selectedKota = computed({
    get() {
        return kota.value.find((kota) => kota.id === form.kota_id);
    },
    set(kota) {
        form.kota_id = kota.id;
    },
});
</script>

<template>
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li>
                <a
                    @click="
                        () => {
                            router.get(route('riwayat-pendidikan.index'));
                        }
                    "
                    >Riwayat Pendidikan</a
                >
            </li>
            <li>
                <span class="text-info">{{ title }}</span>
            </li>
        </ul>
    </div>
    <MainCard :title="title">
        <div class="m-auto w-full p-6 lg:max-w-xl">
            <h2 class="text-center text-2xl font-semibold text-gray-700">
                {{ title }}
            </h2>
            <form class="space-y-4" @submit.prevent="saveRiwayatPendidikan">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Pegawai</span>
                    </label>
                    <vSelect
                        v-model="selectedPegawai"
                        :options="pegawai"
                        label="nama_lengkap"
                        class="w-full"
                    >
                    </vSelect>
                    <label class="label">
                        <span
                            v-if="form.errors.pegawai_id"
                            class="label-text-alt text-error"
                            >{{ form.errors.pegawai_id }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Pendidikan</span>
                    </label>
                    <vSelect
                        v-model="selectedPendidikan"
                        :options="pendidikan"
                        label="nama"
                        class="w-full"
                    >
                    </vSelect>
                    <label class="label">
                        <span
                            v-if="form.errors.pendidikan_id"
                            class="label-text-alt text-error"
                            >{{ form.errors.pendidikan_id }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Instansi</span>
                    </label>
                    <input
                        v-model="form.nama_instansi"
                        type="text"
                        placeholder="Masukkan nama instansi"
                        class="input input-bordered"
                        :class="{ 'textarea-error': form.errors.nama_instansi }"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.nama_instansi"
                            class="label-text-alt text-error"
                            >{{ form.errors.nama_instansi }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Propinsi</span>
                    </label>
                    <vSelect
                        v-model="selectedPropinsi"
                        :options="propinsi"
                        label="nama"
                        class="w-full"
                    >
                    </vSelect>
                    <label class="label">
                        <span
                            v-if="form.errors.propinsi_id"
                            class="label-text-alt text-error"
                            >{{ form.errors.propinsi_id }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kota</span>
                    </label>
                    <vSelect
                        v-model="selectedKota"
                        :options="kota"
                        label="nama"
                        class="w-full"
                    >
                    </vSelect>
                    <label class="label">
                        <span
                            v-if="form.errors.kota_id"
                            class="label-text-alt text-error"
                            >{{ form.errors.kota_id }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Alamat Lengkap</span>
                    </label>
                    <textarea
                        v-model="form.row"
                        class="textarea textarea-bordered h-24"
                        :class="{ 'textarea-error': form.errors.row }"
                        placeholder="Masukkan row lengkap"
                    ></textarea>
                    <label class="label">
                        <span
                            v-if="form.errors.row"
                            class="label-text-alt text-error"
                            >{{ form.errors.row }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tanggal Ijazah</span>
                    </label>
                    <input
                        v-model="form.tanggal_ijazah"
                        type="date"
                        placeholder="Masukkan tanggal ijazah"
                        class="input input-bordered"
                        :class="{
                            'textarea-error': form.errors.tanggal_ijazah,
                        }"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.tanggal_ijazah"
                            class="label-text-alt text-error"
                            >{{ form.errors.tanggal_ijazah }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nomor Ijazah</span>
                    </label>
                    <input
                        v-model="form.no_ijazah"
                        type="text"
                        placeholder="Masukkan nomor ijazah"
                        class="input input-bordered"
                        :class="{ 'textarea-error': form.errors.no_ijazah }"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.no_ijazah"
                            class="label-text-alt text-error"
                            >{{ form.errors.no_ijazah }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Gelar Depan</span>
                    </label>
                    <input
                        v-model="form.kode_gelar_depan"
                        type="text"
                        placeholder="Masukkan gelar depan"
                        class="input input-bordered"
                        :class="{
                            'textarea-error': form.errors.kode_gelar_depan,
                        }"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.kode_gelar_depan"
                            class="label-text-alt text-error"
                            >{{ form.errors.kode_gelar_depan }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Gelar Belakang</span>
                    </label>
                    <input
                        v-model="form.kode_gelar_belakang"
                        type="text"
                        placeholder="Masukkan gelar belakang"
                        class="input input-bordered"
                        :class="{
                            'textarea-error': form.errors.kode_gelar_belakang,
                        }"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.kode_gelar_belakang"
                            class="label-text-alt text-error"
                            >{{ form.errors.kode_gelar_belakang }}</span
                        >
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">File Ijazah</span>
                    </label>
                    <input
                        accept=".pdf"
                        @input="form.media_ijazah = $event.target.files[0]"
                        type="file"
                        placeholder="Masukkan file ijazah"
                        class="file-input file-input-bordered"
                    />
                    <label class="label">
                        <span
                            v-if="form.errors.media_ijazah"
                            class="label-text-alt text-error"
                            >{{ form.errors.media_ijazah }}</span
                        >
                    </label>
                </div>

                <div class="flex justify-end">
                    <a
                        class="btn btn-error mx-2"
                        @click="
                            () => {
                                router.get(route('riwayat-pendidikan.index'));
                            }
                        "
                        >Batal</a
                    >
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </MainCard>
</template>
<style scoped>
@import "vue-select/dist/vue-select.css";
</style>
