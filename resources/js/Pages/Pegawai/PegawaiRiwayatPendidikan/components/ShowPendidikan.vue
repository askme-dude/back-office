<script setup>
import Modal from "@/Components/Modal.vue";
import MainCard from "@/Components/MainCard.vue";
import { useLongLocalDate } from "@/Composables/filters.ts";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    show: { type: Boolean, required: true, default: false },
    riwayat: { type: Object, required: true },
});

defineEmits(["update:show"]);

const mediaIjazahUrl = computed(() => usePage().props.mediaIjazahUrl);
</script>

<template>
    <Modal :key="riwayat.id" :show="show" @close="$emit('update:show', false)">
        <MainCard title="Detail Pendidikan Pegawai">
            <div class="mt-4 grid gap-2">
                <div class="flex">
                    <div class="w-1/3">Nama Pegawai</div>
                    <div class="w-2/3">{{ riwayat.pegawai.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Pendidikan</div>
                    <div class="w-2/3">{{ riwayat.pendidikan.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Gelar</div>
                    <div class="w-2/3">
                        {{
                            riwayat.kode_gelar_depan ||
                            riwayat.kode_gelar_belakang ||
                            "-"
                        }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Nama Instansi</div>
                    <div class="w-2/3">{{ riwayat.nama_instansi }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Alamat</div>
                    <div class="w-2/3">
                        {{ riwayat.alamat }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Propinsi / Kota</div>
                    <div class="w-2/3">
                        {{ riwayat.propinsi?.nama || "-" }} /
                        {{ riwayat.kota?.nama || "-" }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">No Ijazah</div>
                    <div class="w-2/3">
                        {{ riwayat.no_ijazah }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Tanggal Ijazah</div>
                    <div class="w-2/3">
                        {{ useLongLocalDate(new Date(riwayat.tanggal_ijazah)) }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">File Ijazah</div>
                    <div class="w-2/3">
                        <a
                            v-if="mediaIjazahUrl"
                            :href="mediaIjazahUrl"
                            download
                            class="text-blue-600"
                        >
                            Unduh Ijazah
                        </a>
                        <span v-else>-</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
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
