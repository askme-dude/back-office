<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from 'sweetalert2';
//import InputError from "vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/InputError";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    golongan:'',
    //nominal:'',
})

const form = useForm('createUmak',{
    golongan_id:'',
    nominal:'',
});
const simpanData = ()=>{
    form.post('/umak',{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onSuccess:(response)=>{
            Swal.fire({
                title: 'Tersimpan!',
                text: 'Data uang makan berhasil disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            router.get(route('umak.index'));
        },
        onError:(errors)=>{
            if(errors.query){
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Data uang makan gagal disimpan!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}

const back = ()=>{
    router.get(route('umak.index'));
}
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><Link href="/umak">Uang Makan</Link></li>
            <li><span class="text-info">Tambah Uang Makan</span></li>
        </ul>
    </div>

    <MainCard>
        <div class="w-full p-6 m-auto lg:max-w-xl">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Tambah Uang Makan</h2>
            <form class="space-y-4" @submit.prevent="simpanData">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Golongan*</span>
                    </label>
                    <select v-model="form.golongan_id" class="select select-bordered" :class="{'select-error':form.errors.golongan_id}">
                        <option disabled selected>Pilih golongan</option>
                        <option v-for="gol in golongan" :value="gol.id">{{gol.nama}}</option>
                    </select>

                    <!-- <label class="label">
                        <span v-if="form.errors.golongan_id" class="label-text-alt text-error">{{form.errors.golongan_id}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.golongan_id"/>
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

                <div class="flex justify-end">
                    <a class="btn btn-error mx-2" @click="back">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </MainCard>
</template>
