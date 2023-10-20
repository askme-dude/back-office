<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const tambahTukin = ()=>{
    router.get('/tukin/create');
}

const toEdit = (tukin)=>{
    router.get(route('tukin.edit',{tukin}))
}

const toDelete = (tukin)=>{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Menghapus Data Master Tukin",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('tukin.destroy',{tukin}),{
                onSuccess:(response)=>{
                    Swal.fire(
                        'Dihapus!',
                        'Data Master Tukin Berhasil Dihapus!',
                        'success'
                    )
                },
                onError:(errors)=>{
                    if(errors.query){
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Data Master Tukin Gagal Dihapus!',
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
    list_tukin:'',
})

const cari = ref('')
const paginate = ref('10')
watch(cari,debounce (value =>{
    console.log('triger');
        router.get(route('tukin.index'), {cari:value},{
            preserveState:true,
            preserveScroll:true,
            replace:true
    });
},500));
watch(paginate,value =>{
    console.log('triger');
    router.get(route('tukin.index'), {paginate:value},{
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
            <li><span class="text-info">Tunjangan Kinerja</span></li>
        </ul>
    </div>

    <MainCard>
        <div class="overflow-x-auto">
            <div class="py-4 flex justify-between">
                <div class="justify-start">
                    <button class="btn btn-primary" @click="tambahTukin">Tambah</button>
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
                    <th scope="col">Grade Tukin</th>
                    <th scope="col">Nominal Tukin</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover" v-for="(tukin,index) in list_tukin.data" :key="tukin.id">
                    <td scope="col" style="text-align: center;">{{index+1}}</td>
                    <td scope="col" style="text-align: center;">{{tukin.grade}}</td>
                    <td style="text-align: center;">{{tukin.nominal}}</td>
                    <td style="text-align: center;">{{tukin.keterangan}}</td>
                    <td style="text-align: center;">
                        <button class="btn btn-primary btn-xs" @click="toEdit(tukin)">Edit</button>
                        <button class="btn btn-error btn-xs" @click="toDelete(tukin)">Hapus</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <br>
            <div class="join flex justify-end">
                <Component
                :is="link.url?'a':'span'"
                v-for="link in list_tukin.links"
                :href="link.url"
                v-html="link.label"
                class="join-item btn btn-xs"
                :class="{'btn-disabled': !link.url, 'btn-active':link.active}"
                />
            </div>
        </div>
    </MainCard>
</template>
