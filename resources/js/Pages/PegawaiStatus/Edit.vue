<script setup>

import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    dataStatus : '',
    // pegawai:'',
    // propinsi:'',
    // kota:Object,
    // kecamatan:Object,
    // desa:Object,
})
const form = useForm({
    nama:props.dataStatus.nama,
})
const simpanStatus = ()=>{
    form.put(route('pegawai-status.update',props.dataStatus),{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onSuccess:(response)=>{
            Swal.fire({
                title: 'Tersimpan!',
                text: 'Data Status Pegawai Berhasil Diubah!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            router.get(route('pegawai-status.index'));
        },
        onError:(errors)=>{
            if(errors.countError){
                Swal.fire({
                    title: 'Gagal!',
                    text: errors.countError,
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}

const back = ()=>{
    router.get(route('pegawai-status.index'));
}
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><Link href="/master/pegawai-status">List Status Pegawai</Link></li>
            <li><span class="text-info">Edit Status Pegawai</span></li>
        </ul>
    </div>

    <MainCard>
        <div class="w-full p-6 m-auto lg:max-w-xl">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Edit Status Pegawai</h2>
            <form class="space-y-4" @submit.prevent="simpanStatus">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status Pegawai</span>
                    </label>
                    <input v-model="form.nama" type="text" :maxlength="30" placeholder="Masukkan status pegawai" class="input input-bordered" :class="{'input-error':form.errors.nama}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.keterangan" class="label-text-alt text-error">{{form.errors.keterangan}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.nama"/>
                </div>

                <div class="flex justify-end">
                    <a class="btn btn-error mx-2" @click="back">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </MainCard>
</template>

<style scoped>
    
</style>
