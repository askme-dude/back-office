<script setup>
import MainCard from "@/Components/MainCard.vue";
import Table from "@/Pages/Pegawai/PegawaiAlamat/Table.vue";
</script>
<template>
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li><span class="text-info">Alamat</span></li>
        </ul>
    </div>
    <MainCard>
        <Table />
    </MainCard>
    <!-- Open the modal using ID.showModal() method -->
</template>
