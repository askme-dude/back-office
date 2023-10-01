<script setup>
import Breadcrumb from "@/Components/Breadcrumb.vue";
import MainCard from "@/Components/MainCard.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { computed, ref } from "vue";
import PerPageOption from "@/Components/PerPageOption.vue";
import ShowingResultTable from "@/Components/ShowingResultTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import {
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline/index.js";
import { useConfirm } from "@/Composables/sweetalert.ts";
import ShowUnit from "@/Pages/UnitKerja/components/ShowUnit.vue";

defineProps(["unitKerja"]);

const keyword = ref(null);
const openModal = ref(false);
const perPage = ref(15);
const query = computed(() => {
    return {
        filter: { nama: keyword.value },
        per_page: perPage.value,
    };
});

const fetchData = (params = {}) => {
    router.get(
        route("unit-kerja.index"),
        { ...query.value, ...params },
        {
            only: ["unitKerja"],
            replace: true,
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const destroy = async (row) => {
    const confirmed = await useConfirm({ text: "Hapus data Unit Kerja ini" });

    router.delete(route("unit-kerja.destroy", row), {
        onBefore: () => confirmed,
        replace: true,
    });
};

const showUnitKerja = (unit) => {
    router.get(
        route("unit-kerja.index"),
        {
            unit_kerja_id: unit.id,
        },
        {
            only: ["selectedUnit"],
            replace: true,
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => (openModal.value = true),
        },
    );
};
</script>

<template>
    <Head title="Daftar Unit Kerja" />

    <Breadcrumb
        :lists="[
            { label: 'Beranda', url: route('dashboard') },
            { label: 'Unit Kerja' },
        ]"
    />

    <ShowUnit v-model:show="openModal" />

    <MainCard title="Daftar Unit Kerja">
        <div class="mt-8 flex flex-col justify-between gap-2 sm:flex-row">
            <button
                class="btn btn-primary btn-outline"
                @click.prevent="router.get(route('unit-kerja.create'))"
            >
                Rekam Unit Kerja Baru
            </button>
            <div class="flex gap-2">
                <SearchInput :search="() => fetchData()" v-model="keyword" />
                <PerPageOption v-model="perPage" @change="() => fetchData()" />
            </div>
        </div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Unit Kerja</th>
                        <th>Unit Kerja Induk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="unitKerja.data.length < 1">
                        <td colspan="4" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <tr v-else class="hover" v-for="(row, i) in unitKerja.data">
                        <td>{{ unitKerja.from + i }}</td>
                        <td>{{ row.nama }}</td>
                        <td>{{ row.parent?.nama }}</td>
                        <td>
                            <div class="flex gap-1">
                                <PencilSquareIcon
                                    class="h-5 w-5 cursor-pointer text-primary"
                                    @click="
                                        router.get(
                                            route('unit-kerja.edit', row),
                                        )
                                    "
                                />
                                <EyeIcon
                                    class="h-5 w-5 cursor-pointer text-warning"
                                    @click="showUnitKerja(row)"
                                />
                                <TrashIcon
                                    class="h-5 w-5 cursor-pointer text-error"
                                    @click="destroy(row)"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 flex justify-between">
                <ShowingResultTable
                    :total="unitKerja.total"
                    :to="unitKerja.to"
                    :from="unitKerja.from"
                />
                <Pagination
                    :links="unitKerja.links"
                    @goToPage="(page) => fetchData({ page })"
                />
            </div>
        </div>
    </MainCard>
</template>
