<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import SelectOption from "@/Components/SelectOption.vue";
import ErrorText from "@/Components/ErrorText.vue";

const props = defineProps({
    pegawai_id: { type: Number, required: true },
    penghargaan_id: { type: Number, required: true },
    no_sk: { type: String, required: true },
    tanggal_sk: { type: [Date, String], required: true },
    tahun: { type: Number, required: true },
    media_sk: { type: String, required: true },
});

const emit = defineEmits([
    "update:pegawai_id",
    "update:penghargaan_id",
    "update:no_sk",
    "update:tanggal_sk",
    "update:tahun",
    "update:media_sk",
]);

const pegawai = computed(() => usePage().props.pegawai || []);
const currentPegawai = computed(() => usePage().props.currentPegawai);
const penghargaan = computed(() => usePage().props.penghargaan);
const errors = computed(() => usePage().props.errors);

const selectedPegawai = computed({
    get: () =>
        [...pegawai.value, currentPegawai?.value].find(
            (peg) => peg?.id === props.pegawai_id,
        ),
    set: (value) => emit("update:pegawai_id", value.id),
});

const fetchPegawai = (value) => {
    router.get(
        route("riwayat-penghargaan.create"),
        {
            nama: value,
        },
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
    <div class="grid gap-2 lg:grid-cols-2">
        <div class="form-control">
            <label class="label">Pegawai</label>
            <SelectOption
                @search="fetchPegawai"
                :options="pegawai"
                v-model="selectedPegawai"
                :error="Boolean(errors.pegawai_id)"
            />
            <ErrorText :text="errors.pegawai_id" />
        </div>
        <div class="form-control">
            <label class="label">Penghargaan</label>
            <select
                class="select select-bordered"
                :class="{ 'select-error': errors.penghargaan_id }"
                :value="penghargaan_id"
                @change="$emit('update:penghargaan_id', $event.target.value)"
            >
                <option v-for="harga in penghargaan" :value="harga.id">
                    {{ harga.nama }}
                </option>
            </select>
            <ErrorText :text="errors.penghargaan_id" />
        </div>
        <div class="form-control">
            <label class="label">Nomor SK</label>
            <input
                class="input input-bordered"
                :class="{ 'input-error': errors.no_sk }"
                type="text"
                :value="no_sk"
                @input="$emit('update:no_sk', $event.target.value)"
            />
            <ErrorText :text="errors.no_sk" />
        </div>
        <div class="form-control">
            <label class="label">Tanggal SK</label>
            <input
                class="input input-bordered"
                :class="{ 'input-error': errors.tanggal_sk }"
                type="date"
                :value="tanggal_sk"
                @change="$emit('update:tanggal_sk', $event.target.value)"
            />
            <ErrorText :text="errors.tanggal_sk" />
        </div>
        <div class="form-control">
            <label class="label">Tahun</label>
            <input
                class="input input-bordered"
                :class="{ 'input-error': errors.tahun }"
                type="number"
                :value="tahun"
                @input="$emit('update:tahun', $event.target.value)"
            />
            <ErrorText :text="errors.tahun" />
        </div>
        <div class="form-control">
            <label class="label">SK Penghargaan</label>
            <input
                @input="$emit('update:media_sk', $event.target.files[0])"
                type="file"
                class="file-input file-input-bordered"
                :class="{ 'input-error': errors.media_sk }"
            />
            <ErrorText :text="errors.media_sk" />
        </div>
    </div>
</template>
