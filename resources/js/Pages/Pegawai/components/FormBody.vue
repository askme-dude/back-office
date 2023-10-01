<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { useNoPhotoUrl } from "@/Composables/helpers.ts";

const errors = computed(() => usePage().props.errors);
const agama = computed(() => usePage().props.agama);
const golonganDarah = computed(() => usePage().props.golonganDarah);
const statusNikah = computed(() => usePage().props.statusNikah);
const jenisPegawai = computed(() => usePage().props.jenisPegawai);
const statusPegawai = computed(() => usePage().props.statusPegawai);

const noPhotoUrl = useNoPhotoUrl();

const foto_pegawai_url = computed(() => usePage().props.media_foto_pegawai);
const kartu_pegawai_url = computed(() => usePage().props.media_kartu_pegawai);

const props = defineProps({
    showPhoto: {
        type: Boolean,
        default: false,
    },
    nik: String,
    nip: String,
    nama: String,
    jenis_kelamin: String,
    agama_id: String,
    golongan_darah: String,
    jenis_kawin_id: [Number, String],
    tempat_lahir: String,
    tanggal_lahir: Date,
    email_kantor: String,
    email_pribadi: String,
    no_telp: String,
    jenis_pegawai_id: String,
    status_pegawai_id: [Number, String],
    status_dinas: [Number, String],
    no_kartu_pegawai: String,
    tanggal_berhenti: [Date],
    tanggal_wafat: [Date],
    no_bpjs: String,
    no_taspen: String,
    npwp: String,
    no_enroll: String,
    media_foto_pegawai: Object,
    media_kartu_pegawai: Object,
});

defineEmits([
    "update:nik",
    "update:nip",
    "update:nama",
    "update:jenis_kelamin",
    "update:agama_id",
    "update:golongan_darah",
    "update:jenis_kawin_id",
    "update:tempat_lahir",
    "update:tanggal_lahir",
    "update:email_kantor",
    "update:email_pribadi",
    "update:no_telp",
    "update:jenis_pegawai_id",
    "update:status_pegawai_id",
    "update:status_dinas",
    "update:no_kartu_pegawai",
    "update:tanggal_berhenti",
    "update:tanggal_wafat",
    "update:no_bpjs",
    "update:no_taspen",
    "update:npwp",
    "update:no_enroll",
    "update:media_foto_pegawai",
    "update:media_kartu_pegawai",
]);
</script>

<template>
    <div class="grid grid-cols-6 gap-4">
        <div class="form-control col-span-3">
            <label class="label justify-start">
                Nomor Induk Kependudukan
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="nik"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:nik', $event.target.value)"
            />
            <div v-if="errors.nik" class="text-error">
                {{ errors.nik }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start">
                Nomor Induk Pegawai
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="nip"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:nip', $event.target.value)"
            />
            <div v-if="errors.nip" class="text-error">
                {{ errors.nip }}
            </div>
        </div>
        <div class="form-control col-span-6">
            <label class="label justify-start">
                Nama Lengkap
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="nama"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:nama', $event.target.value)"
            />
            <div v-if="errors.nama" class="text-error">
                {{ errors.nama }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Jenis Kelamin
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="jenis_kelamin"
                class="rounded-md dark:bg-white"
                @change="$emit('update:jenis_kelamin', $event.target.value)"
            >
                <option class="dark:text-gray-500" value="L">Laki-laki</option>
                <option class="dark:text-gray-500" value="P">Perempuan</option>
            </select>
            <div v-if="errors.jenis_kelamin" class="text-error">
                {{ errors.jenis_kelamin }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Agama
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="agama_id"
                class="rounded-md dark:bg-white"
                @change="$emit('update:agama_id', $event.target.value)"
            >
                <option
                    v-for="item in agama"
                    class="dark:text-gray-500"
                    :value="item.id"
                    :key="item.id"
                >
                    {{ item.nama }}
                </option>
            </select>
            <div v-if="errors.agama_id" class="text-error">
                {{ errors.agama_id }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Golongan Darah
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="golongan_darah"
                class="rounded-md dark:bg-white"
                @change="$emit('update:golongan_darah', $event.target.value)"
            >
                <option
                    v-for="gol in golonganDarah"
                    :key="gol"
                    class="dark:text-gray-500"
                    :value="gol"
                >
                    {{ gol }}
                </option>
            </select>
            <div v-if="errors.golongan_darah" class="text-error">
                {{ errors.golongan_darah }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Status Nikah
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="jenis_kawin_id"
                class="rounded-md dark:bg-white"
                @change="$emit('update:jenis_kawin_id', $event.target.value)"
            >
                <option
                    v-for="item in statusNikah"
                    class="dark:text-gray-500"
                    :value="item.id"
                    :key="item.id"
                >
                    {{ item.nama }}
                </option>
            </select>
            <div v-if="errors.jenis_kawin_id" class="text-error">
                {{ errors.jenis_kawin_id }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Tempat Lahir
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="tempat_lahir"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:tempat_lahir', $event.target.value)"
            />
            <div v-if="errors.tempat_lahir" class="text-error">
                {{ errors.tempat_lahir }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Tanggal Lahir
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="tanggal_lahir"
                type="date"
                class="input-date rounded-md dark:text-gray-500"
                @input="$emit('update:tanggal_lahir', $event.target.value)"
            />
            <div v-if="errors.tanggal_lahir" class="text-error">
                {{ errors.tanggal_lahir }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Email Kantor
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>

            <input
                :value="email_kantor"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:email_kantor', $event.target.value)"
            />
            <div v-if="errors.email_kantor" class="text-error">
                {{ errors.email_kantor }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start"> Email Pribadi </label>
            <input
                :value="email_pribadi"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:email_pribadi', $event.target.value)"
            />
            <div v-if="errors.email_pribadi" class="text-error">
                {{ errors.email_pribadi }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                No Telepon
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="no_telp"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:no_telp', $event.target.value)"
            />
            <div v-if="errors.no_telp" class="text-error">
                {{ errors.no_telp }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start">
                Jenis Pegawai
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="jenis_pegawai_id"
                class="rounded-md dark:bg-white"
                @change="$emit('update:jenis_pegawai_id', $event.target.value)"
            >
                <option
                    v-for="item in jenisPegawai"
                    class="dark:text-gray-500"
                    :value="item.id"
                    :key="item.id"
                >
                    {{ item.nama }}
                </option>
            </select>
            <div v-if="errors.jenis_pegawai_id" class="text-error">
                {{ errors.jenis_pegawai_id }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start">
                Status Pegawai
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="status_pegawai_id"
                class="rounded-md dark:bg-white"
                @change="$emit('update:status_pegawai_id', $event.target.value)"
            >
                <option
                    v-for="item in statusPegawai"
                    class="dark:text-gray-500"
                    :value="item.id"
                    :key="item.id"
                >
                    {{ item.nama }}
                </option>
            </select>
            <div v-if="errors.status_pegawai_id" class="text-error">
                {{ errors.status_pegawai_id }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start">
                Status Dinas
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <select
                :value="status_dinas"
                class="rounded-md dark:bg-white"
                @change="$emit('update:status_dinas', $event.target.value)"
            >
                <option class="dark:text-gray-500" value="0">Aktif</option>
                <option class="dark:text-gray-500" value="1">
                    Tidak Aktif
                </option>
            </select>
            <div v-if="errors.status_dinas" class="text-error">
                {{ errors.status_dinas }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start"> Tanggal Berhenti </label>
            <input
                :value="tanggal_berhenti"
                type="date"
                class="input-date rounded-md dark:text-gray-500"
                @input="$emit('update:tanggal_berhenti', $event.target.value)"
            />
            <div v-if="errors.tanggal_berhenti" class="text-error">
                {{ errors.tanggal_berhenti }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start"> Tanggal Wafat </label>
            <input
                :value="tanggal_wafat"
                type="date"
                class="input-date rounded-md dark:text-gray-500"
                @input="$emit('update:tanggal_wafat', $event.target.value)"
            />
            <div v-if="errors.tanggal_wafat" class="text-error">
                {{ errors.tanggal_wafat }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start">
                No BPJS
                <span class="ml-1 text-sm text-red-700">*)</span>
            </label>
            <input
                :value="no_bpjs"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:no_bpjs', $event.target.value)"
            />
            <div v-if="errors.no_bpjs" class="text-error">
                {{ errors.no_bpjs }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start"> No Taspen </label>
            <input
                :value="no_taspen"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:no_taspen', $event.target.value)"
            />
            <div v-if="errors.no_taspen" class="text-error">
                {{ errors.no_taspen }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start"> NPWP </label>
            <input
                :value="npwp"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:npwp', $event.target.value)"
            />
            <div v-if="errors.npwp" class="text-error">
                {{ errors.npwp }}
            </div>
        </div>
        <div class="form-control col-span-3">
            <label class="label justify-start"> No Urut Fingerprint </label>
            <input
                :value="no_enroll"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:no_enroll', $event.target.value)"
            />
            <div v-if="errors.no_enroll" class="text-error">
                {{ errors.no_enroll }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label justify-start"> No Kartu Pegawai </label>
            <input
                :value="no_kartu_pegawai"
                type="text"
                class="input-text rounded-md dark:text-gray-500"
                @input="$emit('update:no_kartu_pegawai', $event.target.value)"
            />
            <div v-if="errors.no_kartu_pegawai" class="text-error">
                {{ errors.no_kartu_pegawai }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label"> Kartu Pegawai </label>
            <img
                v-if="showPhoto"
                :src="kartu_pegawai_url || noPhotoUrl"
                class="mb-2 h-full rounded-md border-2"
                alt=""
            />
            <input
                @input="
                    $emit('update:media_kartu_pegawai', $event.target.files[0])
                "
                type="file"
                class="file-input file-input-bordered"
            />
            <div v-if="errors.media_kartu_pegawai" class="text-error">
                {{ errors.media_kartu_pegawai }}
            </div>
        </div>
        <div class="form-control col-span-2">
            <label class="label">Foto Pegawai</label>
            <img
                v-if="showPhoto"
                :src="foto_pegawai_url || noPhotoUrl"
                class="mb-2 h-full rounded-md border-2"
                alt=""
            />
            <input
                @input="
                    $emit('update:media_foto_pegawai', $event.target.files[0])
                "
                type="file"
                class="file-input file-input-bordered"
            />
            <div v-if="errors.media_foto_pegawai" class="text-error">
                {{ errors.media_foto_pegawai }}
            </div>
        </div>
    </div>
</template>
