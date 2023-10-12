<script setup>
import MainCard from "@/Components/MainCard.vue";
import Table from "@/Pages/PegawaiStatus/Table.vue";

defineProps({
    title: String,
})

</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><span class="text-info">List Status Pegawai</span></li>
        </ul>
    </div>
    
    <MainCard>
        <h2 class="text-2xl font-semibold text-center text-gray-700">List Status Pegawai</h2>
        <Table/>
    </MainCard>

</template>
