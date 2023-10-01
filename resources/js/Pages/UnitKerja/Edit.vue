<script setup>
import MainCard from "@/Components/MainCard.vue";
import FormBody from "@/Pages/UnitKerja/components/FormBody.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { router, useForm } from "@inertiajs/vue3";
import { useToast } from "@/Composables/sweetalert.ts";

const props = defineProps(["currentUnit"]);

const form = useForm({
    nama: props.currentUnit.nama,
    parent_id: props.currentUnit.parent_id,
    jenis_unit_kerja_id: props.currentUnit.jenis_unit_kerja_id,
    singkatan: props.currentUnit.singkata,
    keterangan: props.currentUnit.keterangan,
    is_active: props.currentUnit.is_active,
});

const submit = () => {
    form.put(route("unit-kerja.update", props.currentUnit), {
        replace: true,
        preserveState: true,
        onError: (errors) =>
            useToast({ icon: "error", text: Object.values(errors)[0] }),
    });
};
</script>

<template>
    <Head title="Rekam Unit Kerja" />

    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Unit Kerja', url: route('unit-kerja.index') },
            { label: 'Edit Unit Kerja' },
        ]"
    />

    <MainCard title="Edit Data Unit Kerja">
        <form @submit.prevent="submit" class="mx-auto max-w-7xl">
            <FormBody
                v-model:nama="form.nama"
                v-model:is_active="form.is_active"
                v-model:parent_id="form.parent_id"
                v-model:jenis_unit_kerja_id="form.jenis_unit_kerja_id"
                v-model:singkatan="form.singkatan"
                v-model:keterangan="form.keterangan"
            />
            <div class="mt-4 flex justify-end gap-2">
                <button
                    type="reset"
                    class="btn btn-neutral btn-outline"
                    @click="router.get(route('unit-kerja.index'))"
                >
                    Batal
                </button>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </MainCard>
</template>
