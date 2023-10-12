<script setup>
import {  onMounted, ref, watch } from "vue";
import axios from "axios";
import { debounce } from "lodash";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
onMounted(()=>{
    getDataTable(route('unit-kerja.getdatatable'));
})
const list_unit = ref([])
const paginate = ref(10)
const cari = ref('')

const getDataTable = async (value)=>{
    const result = await axios.get(value)
    list_unit.value = result.data
}
watch(cari,debounce (value =>{
    getDataTable(route('unit-kerja.getdatatable')+'?cari='+value+'&paginate='+paginate.value)
},500));
watch(paginate,value =>{
    getDataTable(route('unit-kerja.getdatatable')+'?cari='+cari.value+'&paginate='+value)
});

const tambahUnit = ()=>{
    router.get('/master/unit-kerja/create');
}

const toEdit = (unit_kerja)=>{
    router.get(route('unit-kerja.edit',{unit_kerja}),{},{
        onError:(errors)=>{
            if(errors.query){
                Swal.fire({
                    title: 'Gagal!',
                    text: errors.query,
                    //text: 'Data Master Uang Makan Gagal Diedit!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}
const toDelete = (unit_kerja)=>{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Menghapus Data Master Unit Kerja",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('unit-kerja.destroy',{unit_kerja}),{
                onSuccess:(response)=>{
                    Swal.fire(
                        {
                            title: 'Berhasil!',
                            text: response.props.success,
                            //text: 'Data Master Uang Makan Berhasil Dihapus!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }
                    )
                    getDataTable(route('unit-kerja.getdatatable'))
                },
                onError:(errors)=>{
                    if(errors.query){
                        Swal.fire({
                            title: 'Gagal!',
                            text: errors.query,
                            //text: 'Data Master Uang Makan Gagal Dihapus!',
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
                <button class="btn btn-primary" @click="tambahUnit">Tambah</button>
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
        
        <table class="table" aria-describedby="Tabel Master Unit Kerja">
            <thead>
                    <tr style="text-align: center;">
                        <th scope="col">No</th>
                        <th scope="col">Nama Unit Kerja</th>
                        <th scope="col">Jenis Unit Kerja</th>
                        <th scope="col">Singkatan</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover" v-for="(unit,index) in list_unit.data" :key="unit.id">
                        <td scope="col" style="text-align: center;">{{index+1}}</td>
                        <td style="text-align: center;">{{unit.nama}}</td>
                        <td style="text-align: center;">{{unit.nama_jenis_unit_kerja}}</td>
                        <td style="text-align: center;">{{unit.singkatan}}</td>
                        <td style="text-align: center;">{{unit.keterangan}}</td>
                        <td style="text-align: center;">
                            <button class="text-indigo-600 hover:text-indigo-900" @click="toEdit(unit)">Edit</button>
                            <button class="text-red-600 hover:text-red-900 ml-2" @click="toDelete(unit)">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>
            <div class="join flex justify-end">
                <Component
                    :is="link.url?'a':'span'"
                    v-for="link in list_unit.links"
                    @click="getDataTable(link.url + '&paginate=' + paginate +'&cari='+cari)"
                    v-html="link.label"
                    class="join-item btn btn-xs"
                    :class="{'btn-disabled': !link.url, 'btn-active':link.active}"
                />
            </div>
    </div>
</template>
