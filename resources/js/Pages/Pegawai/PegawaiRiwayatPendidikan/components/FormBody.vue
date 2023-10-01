<script setup>
import { useFetchPegawaiByNama } from "@/Composables/pegawai.ts";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import SelectOption from "@/Components/SelectOption.vue";
import ErrorText from "@/Components/ErrorText.vue";
import axios from "axios";

const props = defineProps({
    pegawai_id: { required: true },
    pendidikan_id: { required: true },
    nama_instansi: { required: true },
    propinsi_id: { required: true },
    kota_id: { required: true },
    alamat: { required: true },
    no_ijazah: { required: true },
    tanggal_ijazah: { required: true },
    kode_gelar_depan: { required: true },
    kode_gelar_belakang: { required: true },
    media_ijazah: { required: true },
});

const emit = defineEmits([
    "update:pegawai_id",
    "update:pendidikan_id",
    "update:nama_instansi",
    "update:propinsi_id",
    "update:kota_id",
    "update:alamat",
    "update:no_ijazah",
    "update:tanggal_ijazah",
    "update:kode_gelar_depan",
    "update:kode_gelar_belakang",
    "update:media_ijazah",
]);

const pegawai = computed(() => usePage().props.pegawai || []);
const riwayatPendidikan = computed(() => usePage().props.riwayatPendidikan);
const currentPegawai = computed(() => usePage().props.currentPegawai);
const pendidikan = computed(() => usePage().props.pendidikan || []);
const currentPendidikan = computed(() => usePage().props.currentPendidikan);
const propinsi = computed(() => usePage().props.propinsi || []);
const kota = computed(() => usePage().props.kota || []);
const errors = computed(() => usePage().props.errors);

const url = computed(() => {
    return route().current("riwayat-pendidikan.create")
        ? route("riwayat-pendidikan.create")
        : route("riwayat-pendidikan.edit", riwayatPendidikan.value.id);
});

const selectedPegawai = computed({
    get: () =>
        [...pegawai.value, currentPegawai.value].find(
            (peg) => peg?.id === props.pegawai_id,
        ),
    set: (pegawai) => emit("update:pegawai_id", pegawai.id),
});

const selectedPendidikan = computed({
    get: () =>
        [...pendidikan.value, currentPendidikan.value].find(
            (pend) => pend?.id === props.pendidikan_id,
        ),
    set: (pend) => emit("update:pendidikan_id", pend.id),
});

const selectedPropinsi = computed({
    get: () => props.propinsi_id,
    set(propinsi_id) {
        emit("update:propinsi_id", propinsi_id);
        fetchKota(propinsi_id);
    },
});

const selectedKota = computed({
    get: () => props.kota_id,
    set: (kota_id) => emit("update:kota_id", kota_id),
});

const fetchPendidikan = (nama) => {
    axios
        .get("/api/ref/pendidikan", {
            params: { filter: { nama }, limit: 10 },
        })
        .then(({ data }) => (usePage().props.pendidikan = data));
};

const fetchKota = (propinsi_id = null) => {
    router.get(
        url.value,
        { propinsi_id },
        {
            only: ["kota"],
            replace: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => emit("update:kota_id", null),
        },
    );
};
</script>

<template>
    <div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Pegawai</span>
            </label>
            <SelectOption
                :error="Boolean(errors.pegawai_id)"
                :options="pegawai"
                v-model="selectedPegawai"
                placeholder="Pilih Pegawai"
                @search="(value) => useFetchPegawaiByNama(url, value)"
            />
            <ErrorText :text="errors.pegawai_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Pendidikan</span>
            </label>
            <SelectOption
                :error="Boolean(errors.pendidikan_id)"
                placeholder="Pilih Pendidikan"
                v-model="selectedPendidikan"
                :options="pendidikan"
                @search="(nama) => fetchPendidikan(nama)"
            />
            <ErrorText :text="errors.pendidikan_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Nama Instansi</span>
            </label>
            <input
                :value="nama_instansi"
                @input="$emit('update:nama_instansi', $event.target.value)"
                type="text"
                placeholder="Masukkan nama instansi"
                class="input input-bordered"
                :class="{ 'textarea-error': errors.nama_instansi }"
            />
            <ErrorText :text="errors.nama_instansi" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Propinsi</span>
            </label>
            <select
                v-model="selectedPropinsi"
                class="select select-bordered"
                :class="{ 'select-error': errors.propinsi_id }"
            >
                <option :value="null" disabled>Pilih Propinsi</option>
                <option
                    v-for="prop in propinsi"
                    :key="prop.id"
                    :value="prop.id"
                >
                    {{ prop.nama }}
                </option>
            </select>
            <ErrorText :text="errors.propinsi_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Kota</span>
            </label>
            <select
                v-model="selectedKota"
                class="select select-bordered"
                :class="{ 'select-error': errors.kota_id }"
            >
                <option :value="null" disabled>Pilih Kota</option>
                <option v-for="kot in kota" :key="kot.id" :value="kot.id">
                    {{ kot.nama }}
                </option>
            </select>
            <ErrorText :text="errors.kota_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Alamat Lengkap</span>
            </label>
            <textarea
                :value="alamat"
                @input="$emit('update:alamat', $event.target.value)"
                class="textarea textarea-bordered h-24"
                :class="{ 'textarea-error': errors.alamat }"
                placeholder="Masukkan alamat lengkap"
            />
            <ErrorText :text="errors.alamat" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Tanggal Ijazah</span>
            </label>
            <input
                :value="tanggal_ijazah"
                @change="$emit('update:tanggal_ijazah', $event.target.value)"
                type="date"
                placeholder="Masukkan tanggal ijazah"
                class="input input-bordered"
                :class="{
                    'textarea-error': errors.tanggal_ijazah,
                }"
            />
            <ErrorText :text="errors.tanggal_ijazah" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Nomor Ijazah</span>
            </label>
            <input
                :value="no_ijazah"
                @input="$emit('update:no_ijazah', $event.target.value)"
                type="text"
                placeholder="Masukkan nomor ijazah"
                class="input input-bordered"
                :class="{ 'textarea-error': errors.no_ijazah }"
            />
            <ErrorText :text="errors.no_ijazah" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Gelar Depan</span>
            </label>
            <input
                :value="kode_gelar_depan"
                @input="$emit('update:kode_gelar_depan', $event.target.value)"
                type="text"
                placeholder="Masukkan gelar depan"
                class="input input-bordered"
                :class="{
                    'textarea-error': errors.kode_gelar_depan,
                }"
            />
            <ErrorText :text="errors.kode_gelar_depan" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Gelar Belakang</span>
            </label>
            <input
                :value="kode_gelar_belakang"
                @input="
                    $emit('update:kode_gelar_belakang', $event.target.value)
                "
                type="text"
                placeholder="Masukkan gelar belakang"
                class="input input-bordered"
                :class="{
                    'textarea-error': errors.kode_gelar_belakang,
                }"
            />
            <ErrorText :text="errors.kode_gelar_belakang" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">File Ijazah</span>
            </label>
            <input
                accept=".pdf,.jpg,.png,.jpeg"
                @input="$emit('update:media_ijazah', $event.target.files[0])"
                type="file"
                placeholder="Masukkan file ijazah"
                class="file-input file-input-bordered"
            />
            <ErrorText :text="errors.media_ijazah" />
        </div>
    </div>
</template>
