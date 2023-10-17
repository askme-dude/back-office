<script setup>
import MainCard from "@/Components/MainCard.vue";
import { useLongLocalDate } from "@/Composables/filters.ts";
import { useConfirm } from "@/Composables/sweetalert.ts";
import { router } from "@inertiajs/vue3";

const props = defineProps(["riwayat", "media_sk"]);

const destroy = async () => {
    const confirmed = await useConfirm({
        text: "Hapus data riwayat penghargaan ini?",
    });

    router.delete(route("riwayat-penghargaan.destroy", props.riwayat), {
        onBefore: () => confirmed,
    });
};
</script>

<template>
    <Head title="Data Penghargaan" />

    <MainCard title="Data Penghargaan">
        <div class="mt-8 flex flex-col gap-2">
            <div class="flex">
                <div class="w-1/4">Nama Pegawai</div>
                <div class="w-3/4">
                    {{ riwayat.pegawai.nama }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">Nama Penghargaan</div>
                <div class="w-3/4">
                    {{ riwayat.penghargaan.nama }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">BKN Riwayat ID</div>
                <div class="w-3/4">
                    {{ riwayat.bkn_id }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">No SK</div>
                <div class="w-3/4">
                    {{ riwayat.no_sk }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">Tanggal SK</div>
                <div class="w-3/4">
                    {{ useLongLocalDate(new Date(riwayat.tanggal_sk)) }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">Tahun</div>
                <div class="w-3/4">
                    {{ riwayat.tahun }}
                </div>
            </div>
            <div class="flex">
                <div class="w-1/4">File</div>
                <div class="w-3/4">
                    <a
                        v-if="riwayat.media[0]"
                        :href="media_sk"
                        download
                        class="text-blue-600 underline"
                    >
                        {{ riwayat.media[0].file_name }}
                    </a>
                    <span v-else>-</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex gap-2">
            <Link
                class="btn btn-neutral"
                :href="route('riwayat-penghargaan.edit', riwayat)"
                >Edit
            </Link>

            <button class="btn btn-error btn-outline" @click="destroy">
                Hapus
            </button>
        </div>
    </MainCard>
</template>
