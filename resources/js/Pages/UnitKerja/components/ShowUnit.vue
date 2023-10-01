<script setup>
import Modal from "@/Components/Modal.vue";
import MainCard from "@/Components/MainCard.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
    show: {
        type: Boolean,
        required: true,
        default: false,
    },
});

defineEmits(["update:show"]);

const selectedUnit = computed(() => usePage().props.selectedUnit);
</script>

<template>
    <Modal :show="show" @close="$emit('update:show', false)">
        <MainCard title="Detail Unit Kerja">
            <div class="mt-4 grid gap-2">
                <div class="flex gap-2">
                    <div class="w-1/3">Nama Unit</div>
                    <div class="w-2/3">{{ selectedUnit.nama }}</div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Jenis Unit Kerja</div>
                    <div class="w-2/3">
                        {{ selectedUnit.jenis_unit_kerja?.nama || "-" }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Unit Kerja Induk</div>
                    <div class="w-2/3">
                        {{ selectedUnit.parent?.nama || "-" }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Status</div>
                    <div class="w-2/3">
                        {{ selectedUnit.is_active ? "Aktif" : "Tidak aktif" }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Singkatan</div>
                    <div class="w-2/3">
                        {{ selectedUnit.singkatan || "-" }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Keterangan</div>
                    <div class="w-2/3">
                        {{ selectedUnit.keterangan || "-" }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1/3">Jumlah Pegawai</div>
                    <div class="w-2/3">
                        {{ selectedUnit.pegawai_count || "-" }}
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="btn btn-neutral btn-outline"
                    @click="$emit('update:show', false)"
                >
                    Kembali
                </button>
            </div>
        </MainCard>
    </Modal>
</template>
