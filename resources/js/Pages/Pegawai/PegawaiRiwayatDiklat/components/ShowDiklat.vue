<script setup>
import Modal from "@/Components/Modal.vue";
import MainCard from "@/Components/MainCard.vue";
import { useLongLocalDate } from "@/Composables/filters.ts";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    show: { type: Boolean, required: true, default: false },
    diklat: { type: Object, required: true },
});

defineEmits(["update:show"]);

const media_sertifikat_url = computed(
    () => usePage().props.media_sertifikat_url,
);
</script>

<template>
    <Modal :key="diklat.id" :show="show" @close="$emit('update:show', false)">
        <MainCard title="Detail Diklat Pegawai">
            <div class="mt-4 grid gap-2">
                <div class="flex">
                    <div class="w-1/3">Nama Pegawai</div>
                    <div class="w-2/3">{{ diklat.pegawai.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Judul Diklat</div>
                    <div class="w-2/3">{{ diklat.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Jenis Diklat</div>
                    <div class="w-2/3">{{ diklat.jenis_diklat.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Tanggal Mulai</div>
                    <div class="w-2/3">
                        {{ useLongLocalDate(new Date(diklat.tanggal_mulai)) }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Tanggal Selesai</div>
                    <div class="w-2/3">
                        {{ useLongLocalDate(new Date(diklat.tanggal_akhir)) }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Penyelenggara</div>
                    <div class="w-2/3">
                        {{ diklat.penyelenggara }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">No Sertifikat</div>
                    <div class="w-2/3">
                        {{ diklat.no_sertifikat }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">Tanggal Sertifikat</div>
                    <div class="w-2/3">
                        {{
                            useLongLocalDate(
                                new Date(diklat.tanggal_sertifikat),
                            )
                        }}
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">File Sertifikat</div>
                    <div class="w-2/3">
                        <a
                            v-if="media_sertifikat_url"
                            :href="media_sertifikat_url"
                            download
                            class="text-blue-600"
                        >
                            Unduh Sertifikat
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
