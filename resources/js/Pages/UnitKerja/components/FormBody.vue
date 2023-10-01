<script setup>
import SelectOption from "@/Components/SelectOption.vue";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import ErrorText from "@/Components/ErrorText.vue";

const props = defineProps({
    nama: {
        required: true,
    },
    is_active: {
        type: Boolean,
        required: true,
    },
    parent_id: {
        required: true,
    },
    jenis_unit_kerja_id: {
        required: true,
    },
    singkatan: {
        required: true,
    },
    keterangan: {
        required: true,
    },
});

const emit = defineEmits([
    "update:nama",
    "update:is_active",
    "update:parent_id",
    "update:jenis_unit_kerja_id",
    "update:singkatan",
    "update:keterangan",
]);

const currentUnit = computed(() => usePage().props.currentUnit);
const unitOptions = computed(() => usePage().props.unitOptions || []);
const jenisUnitKerja = computed(() => usePage().props.jenisUnitKerja);
const errors = computed(() => usePage().props.errors);

const selectedUnit = computed({
    get: () =>
        [...unitOptions.value, currentUnit.value?.parent].find(
            (unit) => unit?.id === props.parent_id,
        ),
    set: (unit) => emit("update:parent_id", unit.id),
});

const fetchUnitOptions = (nama) => {
    const url = route().current("unit-kerja.create")
        ? route("unit-kerja.create")
        : route("unit-kerja.edit", currentUnit.value);

    router.get(
        url,
        { filter: { nama } },
        {
            only: ["unitOptions"],
            replace: true,
            preserveState: true,
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div class="grid gap-2 lg:grid-cols-2 lg:gap-x-4">
        <div class="form-control">
            <label class="label">Nama Unit</label>
            <input
                class="input input-bordered"
                :class="{ 'input-error': errors.nama }"
                :value="nama"
                @input="$emit('update:nama', $event.target.value)"
            />
            <ErrorText :text="errors.nama" />
        </div>
        <div class="form-control">
            <label class="label">Jenis Unit Kerja</label>
            <select
                :value="jenis_unit_kerja_id"
                @change="
                    $emit('update:jenis_unit_kerja_id', $event.target.value)
                "
                class="select select-bordered"
                :class="{ 'select-error': errors.jenis_unit_kerja_id }"
            >
                <option disabled :value="null">Pilih Jenis Unit</option>
                <option
                    v-for="jenis in jenisUnitKerja"
                    :key="jenis.id"
                    :value="jenis.id"
                >
                    {{ jenis.nama }}
                </option>
            </select>
            <ErrorText :text="errors.jenis_unit_kerja_id" />
        </div>
        <div class="form-control">
            <label class="label">Unit Kerja Induk</label>
            <SelectOption
                :options="unitOptions"
                v-model="selectedUnit"
                placeholder="Pilih Unit Kerja Induk"
                :error="Boolean(errors.parent_id)"
                @search="fetchUnitOptions"
            />
            <ErrorText :text="errors.parent_id" />
        </div>
        <div class="form-control">
            <label class="label">Singkatan</label>
            <input
                type="text"
                class="input input-bordered"
                :value="singkatan"
                @input="$emit('update:singkatan', $event.target.value)"
            />
            <ErrorText :text="errors.singkatan" />
        </div>
        <div class="form-control">
            <label class="label">Keterangan</label>
            <textarea
                class="input input-bordered"
                :value="keterangan"
                @input="$emit('update:keterangan', $event.target.value)"
            />
            <ErrorText :text="errors.keterangan" />
        </div>
        <div class="form-control">
            <label class="label">Aktif</label>
            <input
                type="checkbox"
                class="checkbox"
                :checked="is_active"
                @input="$emit('update:is_active', $event.target.checked)"
            />
            <ErrorText :text="errors.is_active" />
        </div>
    </div>
</template>
