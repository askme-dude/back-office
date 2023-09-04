<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const tambahUmak = ()=>{
    router.get('/master/umak/create');
}

const toEdit = (umak)=>{
    router.get(route('umak.edit',{umak}))
}

const toDelete = (umak)=>{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Menghapus Data Master Uang Makan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('umak.destroy',{umak}),{
                onSuccess:(response)=>{
                    Swal.fire(
                        'Dihapus!',
                        'Data Master Uang Makan Berhasil Dihapus!',
                        'success'
                    )
                },
                onError:(errors)=>{
                    if(errors.query){
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Data Master Uang Makan Gagal Dihapus!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })

        }
    })
}

defineProps({
    list_umak:'',
})

const cari = ref('')
const paginate = ref('10')
watch(cari,debounce (value =>{
    console.log('triger');
        router.get(route('umak.index'), {cari:value},{
            preserveState:true,
            preserveScroll:true,
            replace:true
    });
},500));
watch(paginate,value =>{
    console.log('triger');
    router.get(route('umak.index'), {paginate:value},{
        preserveState:true,
        preserveScroll:true,
        replace:true
    });
});
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Master</li>
            <li><span class="text-info">List Uang Makan</span></li>
        </ul>
    </div>
    
    <MainCard>
        <div class="overflow-x-auto">
            <div class="py-4 flex justify-between">
                <div class="justify-start">
                    <button class="btn btn-primary" @click="tambahUmak">Tambah</button>
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
            
            <table class="table" aria-describedby="Tabel Master Uang Makan">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col">No</th>
                        <th scope="col">Golongan</th>
                        <th scope="col">Nominal</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover" v-for="(umak,index) in list_umak.data" :key="umak.id">
                        <td scope="col" style="text-align: center;">{{index+1}}</td>
                        <td style="text-align: center;">{{umak.nama_golongan}}</td>
                        <td style="text-align: center;">{{umak.nominal}}</td>
                        <td style="text-align: center;">
                            <button class="text-indigo-600 hover:text-indigo-900" @click="toEdit(umak)">Edit</button>
                            <button class="text-red-600 hover:text-red-900 ml-2" @click="toDelete(umak)">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>
            <div class="join flex justify-end">
                <Component
                :is="link.url?'a':'span'"
                v-for="link in list_umak.links"
                :href="link.url"
                v-html="link.label"
                class="join-item btn btn-xs"
                :class="{'btn-disabled': !link.url, 'btn-active':link.active}"
                />
            </div>
        </div>
    </MainCard>
</template>
