<script setup>
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SelectOption from "@/Components/SelectOption.vue";
import ErrorText from "@/Components/ErrorText.vue";

const pegawai = computed(() => usePage().props.pegawai);
const jenisDiklat = computed(() => usePage().props.jenisDiklat);
const riwayatDiklat = computed(() => usePage().props.riwayatDiklat);
const currentPegawai = computed(() => usePage().props.currentPegawai);
const errors = computed(() => usePage().props.errors);

const props = defineProps({
    pegawai_id: { required: true },
    nama: { required: true },
    jenis_diklat_id: { required: true },
    tanggal_mulai: { required: true },
    tanggal_akhir: { required: true },
    jam_pelajaran: { required: true },
    lokasi: { required: true },
    penyelenggara: { required: true },
    no_sertifikat: { required: true },
    tanggal_sertifikat: { required: true },
    media_sertifikat: { required: true },
});

const emit = defineEmits([
    "update:pegawai_id",
    "update:nama",
    "update:jenis_diklat_id",
    "update:tanggal_mulai",
    "update:tanggal_akhir",
    "update:jam_pelajaran",
    "update:lokasi",
    "update:penyelenggara",
    "update:no_sertifikat",
    "update:tanggal_sertifikat",
    "update:media_sertifikat",
]);

const url = computed(() => {
    return route().current("riwayat-diklat.create")
        ? route("riwayat-diklat.create")
        : route("riwayat-diklat.edit", riwayatDiklat.value.id);
});

const selectedPegawai = computed({
    get: () =>
        [...pegawai.value, currentPegawai.value].find(
            (peg) => peg?.id === props.pegawai_id,
        ),
    set: (pegawai) => emit("update:pegawai_id", pegawai.id),
});

const fetchPegawai = (nama) => {
    router.get(
        url.value,
        { filter: { nama } },
        {
            only: ["pegawai"],
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};
</script>

<template>
    <div class="grid grid-cols-2 gap-2">
        <div class="form-control col-span-2">
            <label class="label">
                <span class="label-text">Pegawai</span>
            </label>
            <SelectOption
                :error="Boolean(errors.pegawai_id)"
                :options="pegawai"
                v-model="selectedPegawai"
                placeholder="Pilih Pegawai"
                @search="fetchPegawai"
            />
            <ErrorText :text="errors.pegawai_id" />
        </div>
        <div class="form-control col-span-2">
            <label class="label">
                <span class="label-text">Nama Diklat</span>
            </label>
            <input
                :value="nama"
                @input="$emit('update:nama', $event.target.value)"
                type="text"
                placeholder="Nama Diklat"
                class="input input-bordered"
            />
            <ErrorText :text="errors.nama" />
        </div>
        <div class="form-control col-span-2">
            <label class="label">
                <span class="label-text">Jenis Diklat</span>
            </label>
            <select
                class="select select-bordered"
                :value="jenis_diklat_id"
                @change="$emit('update:jenis_diklat_id', $event.target.value)"
            >
                <option :value="null" disabled>Pilih Jenis Diklat</option>
                <option
                    v-for="jenis in jenisDiklat"
                    :key="jenis.id"
                    :value="jenis.id"
                >
                    {{ jenis.nama }}
                </option>
            </select>
            <ErrorText :text="errors.jenis_diklat_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Tanggal Mulai</span>
            </label>
            <input
                :value="tanggal_mulai"
                type="date"
                placeholder="Masukkan tanggal akhir"
                @change="$emit('update:tanggal_mulai', $event.target.value)"
                :max="tanggal_akhir"
                class="input input-bordered"
            />
            <ErrorText :text="errors.tanggal_mulai" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Tanggal Selesai</span>
            </label>
            <input
                :value="tanggal_akhir"
                type="date"
                placeholder="Masukkan tanggal selesai"
                @change="$emit('update:tanggal_akhir', $event.target.value)"
                :min="tanggal_mulai"
                class="input input-bordered"
            />
            <ErrorText :text="errors.tanggal_akhir" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Jam Pelajaran</span>
            </label>
            <input
                :value="jam_pelajaran"
                @input="$emit('update:jam_pelajaran', $event.target.value)"
                type="number"
                placeholder="Jumlah jam pelajaran"
                class="input input-bordered"
            />
            <ErrorText :text="errors.jam_pelajaran" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Lokasi</span>
            </label>
            <input
                :value="lokasi"
                @input="$emit('update:lokasi', $event.target.value)"
                type="text"
                placeholder="Lokasi Diklat"
                class="input input-bordered"
            />
            <ErrorText :text="errors.lokasi" />
        </div>
        <div class="form-control col-span-2">
            <label class="label">
                <span class="label-text">Penyelenggara</span>
            </label>
            <input
                :value="penyelenggara"
                @input="$emit('update:penyelenggara', $event.target.value)"
                type="text"
                placeholder="Nama instansi penyelenggara"
                class="input input-bordered"
            />
            <ErrorText :text="errors.penyelenggara" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Nomor Sertifikat</span>
            </label>
            <input
                :value="no_sertifikat"
                @input="$emit('update:no_sertifikat', $event.target.value)"
                type="text"
                placeholder="Masukkan nomor sertifikat"
                class="input input-bordered"
            />
            <ErrorText :text="errors.no_sertifikat" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Tanggal Sertifikat</span>
            </label>
            <input
                :value="tanggal_sertifikat"
                @input="$emit('update:tanggal_sertifikat', $event.target.value)"
                type="date"
                :min="tanggal_akhir"
                placeholder="Masukkan tanggal sertifikat"
                class="input input-bordered"
            />
            <ErrorText :text="errors.tanggal_sertifikat" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">File Sertifikat</span>
            </label>
            <input
                accept=".pdf,.jpg,.png,.jpeg"
                @input="
                    $emit('update:media_sertifikat', $event.target.files[0])
                "
                type="file"
                placeholder="Masukkan file sertifikat"
                class="file-input file-input-bordered"
            />
            <ErrorText :text="errors.media_sertifikat" />
        </div>
    </div>
</template>
