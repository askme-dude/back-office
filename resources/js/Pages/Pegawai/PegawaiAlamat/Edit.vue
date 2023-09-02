<script setup>

import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import Swal from "sweetalert2";
import vSelect from 'vue-select'
const props = defineProps({
    pegawaiAlamat : '',
    pegawai:'',
    propinsi:'',
    kota:Object,
    kecamatan:Object,
    desa:Object,
})
const form = useForm({
    tipe_alamat:props.pegawaiAlamat.tipe_alamat,
    propinsi_id:props.pegawaiAlamat.propinsi_id,
    kota_id:props.pegawaiAlamat.kota_id,
    kecamatan_id:props.pegawaiAlamat.kecamatan_id,
    desa_id:props.pegawaiAlamat.desa_id,
    kode_pos:props.pegawaiAlamat.kode_pos,
    alamat:props.pegawaiAlamat.alamat,
    pegawai_id:props.pegawaiAlamat.pegawai_id
})
const simpanAlamat = ()=>{
    form.put(route('alamat.update',props.pegawaiAlamat.id),{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onSuccess:(response)=>{
            Swal.fire({
                title: 'Tersimpan!',
                text: 'alamat pegawai berhasil diubah',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            router.get(route('alamat.index'));
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

const kota = ref(props.kota);
const kecamatan = ref(props.kecamatan);
const desa = ref(props.desa);
watch(()=>form.propinsi_id,(value)=>{
    router.get(route('alamat.edit',props.pegawaiAlamat.id),{propinsi_id:value},{
        preserveState:true,
        preserveScroll:true,
        onSuccess:(response)=>{
            form.kota_id = null;
            form.kecamatan_id = null;
            form.desa_id = null;
            kota.value = response.props.kota
        }
    });
});
const selectedPropinsi = computed({
    get(){
        return props.propinsi.find(prop => prop.id === form.propinsi_id)
    },
    set(propinsi){
        form.propinsi_id = propinsi.id
    }
})
watch(()=>form.kota_id,(value)=>{
    router.get(route('alamat.edit',props.pegawaiAlamat.id),{kota_id:value},{
        preserveState:true,
        preserveScroll:true,
        onSuccess:(response)=>{
            kecamatan.value = response.props.kecamatan
        }
    });
});
const selectedKota = computed({
    get(){
        return kota.value?.find(kot => kot.id === form.kota_id)
    },
    set(kota){
        form.kota_id = kota.id
    }
})
watch(()=>form.kecamatan_id,(value)=>{
    router.get(route('alamat.edit',props.pegawaiAlamat.id),{kecamatan_id:value},{
        preserveState:true,
        preserveScroll:true,
        onSuccess:(response)=>
        {
            desa.value = response.props.desa
        },
    });
});
const selectedKecamatan = computed({
    get(){
        return kecamatan.value.find(kec => kec.id === form.kecamatan_id)
    },
    set(kecamatan){
        form.kecamatan_id = kecamatan.id
    }
})
const selectedDesa = computed({
    get(){
        console.log(form.desa_id);
        return  desa.value?.find(des => des.id === form.desa_id)
    },
    set(desa){
        form.desa_id = desa.id
    }
})
const back = ()=>{
    router.get(route('alamat.index'));
}


</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li><Link href="/pegawai/alamat">Alamat</Link></li>
            <li><span class="text-info">Edit Alamat</span></li>
        </ul>
    </div>
    <MainCard>
        <div class="w-full p-6 m-auto lg:max-w-xl">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Ubah Alamat</h2>
            <form class="space-y-4" @submit.prevent="simpanAlamat">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Pegawai</span>
                    </label>
                    <input class="input input-bordered" disabled :class="{'input-error':form.errors.kode_pos}" :value="pegawai.nama_depan+ ' '+  pegawai.nama_belakang"/>
                    <label class="label">
                        <span v-if="form.errors.pegawai_id" class="label-text-alt text-error">{{form.errors.pegawai_id}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tipe</span>
                    </label>

                    <select v-model="form.tipe_alamat" class="select select-bordered" :class="{'select-error':form.errors.tipe_alamat}">
                        <option disabled selected>Pilih tipe</option>
                        <option value="D">Domisili</option>
                        <option value="K">Asal</option>
                    </select>
                    <label class="label">
                        <span v-if="form.errors.tipe_alamat" class="label-text-alt text-error">{{form.errors.tipe_alamat}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Propinsi</span>
                    </label>
                    <vSelect v-model="selectedPropinsi" :options="propinsi" label="nama" class="w-full">
                    </vSelect>
                    <label class="label">
                        <span v-if="form.errors.propinsi_id" class="label-text-alt text-error">{{form.errors.propinsi_id}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kota/Kabupaten</span>
                    </label>

                    <vSelect v-model="selectedKota" :options="kota" label="nama" class="w-full" >
                    </vSelect>
                    <label class="label">
                        <span v-if="form.errors.kota_id" class="label-text-alt text-error">{{form.errors.kota_id}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kecamatan</span>
                    </label>

                    <vSelect v-model="selectedKecamatan" :options="kecamatan" label="nama" class="w-full" >
                    </vSelect>
                    <label class="label">
                        <span v-if="form.errors.kecamatan_id" class="label-text-alt text-error">{{form.errors.kecamatan_id}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Desa</span>
                    </label>
                    <vSelect v-model="selectedDesa" :options="desa" label="nama" class="w-full" >
                    </vSelect>
                    <label class="label">
                        <span v-if="form.errors.desa_id" class="label-text-alt text-error">{{form.errors.desa_id}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kode Pos</span>
                    </label>
                    <input v-model="form.kode_pos" type="number" placeholder="Masukkan kode pos" class="input input-bordered" :class="{'input-error':form.errors.kode_pos}" />
                    <label class="label">
                        <span v-if="form.errors.kode_pos" class="label-text-alt text-error">{{form.errors.kode_pos}}</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Alamat Lengkap</span>
                    </label>
                    <textarea v-model="form.alamat" class="textarea textarea-bordered h-24" :class="{'textarea-error':form.errors.alamat}" placeholder="Masukkan alamat lengkap"></textarea>
                    <label class="label">
                        <span v-if="form.errors.alamat" class="label-text-alt text-error">{{form.errors.alamat}}</span>
                    </label>
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
