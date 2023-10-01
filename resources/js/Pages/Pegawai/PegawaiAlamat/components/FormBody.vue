<script setup>
import ErrorText from "@/Components/ErrorText.vue";
import SelectOption from "@/Components/SelectOption.vue";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const props = defineProps({
    pegawai_id: {
        required: true,
    },
    tipe_alamat: {
        required: true,
    },
    propinsi_id: {
        required: true,
    },
    kota_id: {
        required: true,
    },
    kecamatan_id: {
        required: true,
    },
    desa_id: {
        required: true,
    },
    kode_pos: {
        required: true,
    },
    alamat: {
        required: true,
    },
});
const emit = defineEmits([
    "update:pegawai_id",
    "update:tipe_alamat",
    "update:propinsi_id",
    "update:kota_id",
    "update:kecamatan_id",
    "update:desa_id",
    "update:kode_pos",
    "update:alamat",
]);

const pegawai = computed(() => usePage().props.pegawai || []);
const currentPegawai = computed(() => usePage().props.currentPegawai);
const propinsi = computed(() => usePage().props.propinsi);
const listOptions = computed(() => {
    const kota = usePage().props.kota;
    const kecamatan = usePage().props.kecamatan;
    const desa = usePage().props.desa;

    return { kota, kecamatan, desa };
});
const errors = computed(() => usePage().props.errors);

const getOptions = (label) => {
    return listOptions.value[label] || JSON.parse(localStorage.getItem(label));
};

const selectedPegawai = computed({
    get: () =>
        [...pegawai.value, currentPegawai.value].find(
            (peg) => peg?.id === props.pegawai_id,
        ),
    set: (pegawai) => emit("update:pegawai_id", pegawai.id),
});
const selectedTipeAlamat = computed({
    get: () => props.tipe_alamat,
    set: (val) => emit("update:tipe_alamat", val),
});
const selectedPropinsi = computed({
    get: () => props.propinsi_id,
    set: (propinsi_id) => {
        fetchData("kota", { propinsi_id });
        emit("update:propinsi_id", propinsi_id);
    },
});
const selectedKota = computed({
    get: () => props.kota_id,
    set: (kota_id) => {
        fetchData("kecamatan", { kota_id });
        emit("update:kota_id", kota_id);
    },
});
const selectedKecamatan = computed({
    get: () => props.kecamatan_id,
    set: (kecamatan_id) => {
        fetchData("desa", { kecamatan_id });
        emit("update:kecamatan_id", kecamatan_id);
    },
});
const selectedDesa = computed({
    get: () => props.desa_id,
    set: (desa_id) => emit("update:desa_id", desa_id),
});

const existingAlamat = computed(() => usePage().props.pegawaiAlamat);

const url = computed(() => {
    return route().current("alamat.create")
        ? route("alamat.create")
        : route("alamat.edit", existingAlamat.value.id);
});

const fetchPegawai = (nama) => {
    router.get(
        url.value,
        { filter: { nama } },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ["pegawai"],
        },
    );
};

const fetchData = (label, params) => {
    router.get(url.value, params, {
        only: [label],
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onFinish: () => {
            localStorage.setItem(
                label,
                JSON.stringify(listOptions.value[label]),
            );
            if (label === "kota") {
                emit("update:kota_id", null);
                emit("update:kecamatan_id", null);
                emit("update:desa_id", null);
            } else if (label === "kecamatan") {
                emit("update:kecamatan_id", null);
                emit("update:desa_id", null);
            } else if (label === "desa") {
                emit("update:desa_id", null);
            }
        },
    });
};
</script>

<template>
    <div class="grid grid-cols-1 gap-x-2 gap-y-4 md:grid-cols-2 lg:grid-cols-3">
        <div class="form-control">
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
        <div class="form-control">
            <label class="label">
                <span class="label-text">Tipe</span>
            </label>
            <select
                v-model="selectedTipeAlamat"
                class="select select-bordered"
                :class="{ 'select-error': errors.tipe_alamat }"
            >
                <option disabled :value="null">Pilih tipe</option>
                <option value="Domisili">Domisili</option>
                <option value="Asal">Asal</option>
            </select>
            <ErrorText :text="errors.tipe_alamat" />
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
                <option disabled :value="null">Pilih propinsi</option>
                <option v-for="prop in propinsi" :value="prop.id">
                    {{ prop.nama }}
                </option>
            </select>
            <ErrorText :text="errors.propinsi_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Kota/Kabupaten</span>
            </label>
            <select
                v-model="selectedKota"
                class="select select-bordered"
                :class="{ 'select-error': errors.kota_id }"
            >
                <option disabled :value="null">Pilih kota/kabupaten</option>
                <option v-for="kot in getOptions('kota')" :value="kot.id">
                    {{ kot.nama }}
                </option>
            </select>
            <ErrorText :text="errors.kota_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Kecamatan</span>
            </label>
            <select
                v-model="selectedKecamatan"
                class="select select-bordered"
                :class="{
                    'select-error': errors.kecamatan_id,
                }"
            >
                <option disabled :value="null">Pilih kecamatan</option>
                <option v-for="kec in getOptions('kecamatan')" :value="kec.id">
                    {{ kec.nama }}
                </option>
            </select>
            <ErrorText :text="errors.kecamatan_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Desa/Kelurahan</span>
            </label>
            <select
                v-model="selectedDesa"
                class="select select-bordered"
                :class="{ 'select-error': errors.desa_id }"
            >
                <option disabled :value="null">Pilih desa</option>
                <option v-for="des in getOptions('desa')" :value="des.id">
                    {{ des.nama }}
                </option>
            </select>
            <ErrorText :text="errors.desa_id" />
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Kode Pos</span>
            </label>
            <input
                :value="kode_pos"
                @input="$emit('update:kode_pos', $event.target.value)"
                type="number"
                placeholder="Masukkan kode pos"
                class="input input-bordered"
                :class="{ 'input-error': errors.kode_pos }"
            />
            <ErrorText :text="errors.kode_pos" />
        </div>
        <div class="form-control lg:col-span-2">
            <label class="label">
                <span class="label-text">Alamat Lengkap</span>
            </label>
            <textarea
                :value="alamat"
                @input="$emit('update:alamat', $event.target.value)"
                class="textarea textarea-bordered h-24"
                :class="{ 'textarea-error': errors.alamat }"
                placeholder="Masukkan alamat lengkap"
            ></textarea>
            <ErrorText :text="errors.alamat" />
        </div>
    </div>
</template>
