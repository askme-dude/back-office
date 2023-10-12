<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from 'sweetalert2';
//import InputError from "vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/InputError";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    jenisUnit:'',
    //nominal:'',
})

const form = useForm('createUnit',{
    nama:'',
    jenis_unit_kerja_id:'',
    singkatan:'',
    keterangan:'',
});
const simpanData = ()=>{
    form.post('/master/unit-kerja',{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onSuccess:(response)=>{
            Swal.fire({
                title: 'Tersimpan!',
                text: 'Data unit kerja berhasil disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            router.get(route('unit-kerja.index'));
        },
        onError:(errors)=>{
            if(errors.query){
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Data unit kerja gagal disimpan!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}

const back = ()=>{
    router.get(route('unit-kerja.index'));
}
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><Link href="/master/unit-kerja">List Unit Kerja</Link></li>
            <li><span class="text-info">Tambah Unit Kerja</span></li>
        </ul>
    </div>

    <MainCard>
        <div class="w-full p-6 m-auto lg:max-w-xl">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Tambah Unit Kerja</h2>
            <form class="space-y-4" @submit.prevent="simpanData">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Unit Kerja*</span>
                    </label>
                    <input v-model="form.nama" type="text" :maxlength="255" placeholder="Masukkan nama unit kerja" class="input input-bordered" :class="{'input-error':form.errors.nama}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.nominal" class="label-text-alt text-error">{{form.errors.nominal}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.nama"/>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Jenis Unit Kerja*</span>
                    </label>
                    <select v-model="form.jenis_unit_kerja_id" class="select select-bordered" :class="{'select-error':form.errors.jenis_unit_kerja_id}" placeholder="Pilih Golongan">
                        <option disabled selected>Pilih golongan</option>
                        <option v-for="ju in jenisUnit" :value="ju.id">{{ju.nama}}</option>
                    </select>

                    <!-- <label class="label">
                        <span v-if="form.errors.golongan_id" class="label-text-alt text-error">{{form.errors.golongan_id}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.jenis_unit_kerja_id"/>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Singkatan</span>
                    </label>
                    <input v-model="form.singkatan" type="text" :maxlength="18" placeholder="Masukkan singkatan unit kerja" class="input input-bordered" :class="{'input-error':form.errors.singkatan}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.nominal" class="label-text-alt text-error">{{form.errors.nominal}}</span>
                    </label> -->
                    <InputError
                        class="mt-2"
                        :message="form.errors.singkatan"/>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Keterangan</span>
                    </label>
                    <input v-model="form.keterangan" type="text" :maxlength="100" placeholder="Masukkan keterangan unit kerja" class="input input-bordered" :class="{'input-error':form.errors.keterangan}" />
                    
                    <!-- <label class="label">
                        <span v-if="form.errors.nominal" class="label-text-alt text-error">{{form.errors.nominal}}</span>
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
