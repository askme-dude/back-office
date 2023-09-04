<script setup>

import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    dataTukin : '',
    // pegawai:'',
    // propinsi:'',
    // kota:Object,
    // kecamatan:Object,
    // desa:Object,
})
const form = useForm({
    grade:props.dataTukin.grade,
    nominal:props.dataTukin.nominal,
    keterangan:props.dataTukin.keterangan,
})
const simpanTukin = ()=>{
    form.put(route('tukin.update',props.dataTukin),{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onSuccess:(response)=>{
            Swal.fire({
                title: 'Tersimpan!',
                text: 'Data Tunjangan Kinerja Berhasil Diubah!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            router.get(route('tukin.index'));
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
    router.get(route('tukin.index'));
}
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><Link href="/master/tukin">List Tunjangan Kinerja</Link></li>
            <li><span class="text-info">Edit Tunjangan Kinerja</span></li>
        </ul>
    </div>

    <MainCard>
        <div class="w-full p-6 m-auto lg:max-w-xl">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Edit Tunjangan Kinerja</h2>
            <form class="space-y-4" @submit.prevent="simpanTukin">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Grade*</span>
                    </label>
                    <input v-model="form.grade" type="number" :maxlength="9999" placeholder="Masukkan grade" class="input input-bordered" :class="{'input-error':form.errors.grade}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.grade" class="label-text-alt text-error">{{form.errors.grade}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.grade"/>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nominal*</span>
                    </label>
                    <input v-model="form.nominal" type="number" :maxlength="999999999999999" placeholder="Masukkan nominal" class="input input-bordered" :class="{'input-error':form.errors.nominal}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.nominal" class="label-text-alt text-error">{{form.errors.nominal}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.nominal"/>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Keterangan</span>
                    </label>
                    <input v-model="form.keterangan" type="text" :maxlength="255" placeholder="Masukkan keterangan" class="input input-bordered" :class="{'input-error':form.errors.keterangan}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.keterangan" class="label-text-alt text-error">{{form.errors.keterangan}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.keterangan"/>
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
