<script setup>
import {  onMounted, ref, watch } from "vue";
import axios from "axios";
import { debounce } from "lodash";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

onMounted(()=>{
    getDataTable(route('pegawai-status.getdatatable'));
})
const list_status = ref([])
const paginate = ref(10)
const cari = ref('')

const getDataTable = async (value)=>{
    const result = await axios.get(value)
    list_status.value = result.data
}
watch(cari,debounce (value =>{
    getDataTable(route('pegawai-status.getdatatable')+'?cari='+value+'&paginate='+paginate.value)
},500));
watch(paginate,value =>{
    getDataTable(route('pegawai-status.getdatatable')+'?cari='+cari.value+'&paginate='+value)
});

const tambahStatus = ()=>{
    router.get('/master/pegawai-status/create');
}

const toEdit = (pegawai_status)=>{
    router.get(route('pegawai-status.edit',{pegawai_status}),{},{
        onError:(errors)=>{
            if(errors.query){
                Swal.fire({
                    title: 'Gagal!',
                    text: errors.query,
                    //text: 'Data Master Tukin Gagal Diedit!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}
const toDelete = (pegawai_status)=>{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Menghapus Data Master Status Pegawai",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('pegawai-status.destroy',{pegawai_status}),{
                onSuccess:(response)=>{
                    Swal.fire(
                        {
                            title: 'Berhasil!',
                            text: response.props.success,
                            //text: 'Data Master Tukin Berhasil Dihapus!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }
                    )
                    getDataTable(route('pegawai-status.getdatatable'))
                },
                onError:(errors)=>{
                    if(errors.query){
                        Swal.fire({
                            title: 'Gagal!',
                            text: errors.query,
                            //text: 'Data Master Tukin Gagal Dihapus!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })

        }
    })

}

</script>

<template>
    <div class="overflow-x-auto">
        <div class="py-4 flex justify-between">
            <div class="justify-start">
                <button class="btn btn-primary" @click="tambahStatus">Tambah</button>
            </div>
            <div class="justify-end">
                <input v-model="cari" type="text" placeholder="Cari" class="input input-bordered w-auto max-w-xs mr-2" />
                <select v-model="paginate"  class="select select-bordered w-auto max-w-xs">
                    <option value="5">5</option>
                    <option value="10" >10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        
        <table class="table" aria-describedby="Tabel Master Tunjangan Kinerja">
                <thead>
                <tr style="text-align: center;">
                    <th scope="col">No</th>
                    <th scope="col">Status Pegawai</th>
                    <th scope="col">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover" v-for="(status,index) in list_status.data" :key="status.id">
                    <td scope="col" style="text-align: center;">{{index+1}}</td>
                    <td scope="col" style="text-align: center;">{{status.nama}}</td>
                    <td style="text-align: center;">
                        <button class="text-indigo-600 hover:text-indigo-900" @click="toEdit(status)">Edit</button>
                        <button class="text-red-600 hover:text-red-900 ml-2" @click="toDelete(status)">Hapus</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <br>
            <div class="join flex justify-end">
                <Component
                    :is="link.url?'a':'span'"
                    v-for="link in list_status.links"
                    @click="getDataTable(link.url + '&paginate=' + paginate +'&cari='+cari)"
                    v-html="link.label"
                    class="join-item btn btn-xs"
                    :class="{'btn-disabled': !link.url, 'btn-active':link.active}"
                />
            </div>
    </div>
</template>
