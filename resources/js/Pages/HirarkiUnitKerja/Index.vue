<script setup>
import MainCard from "@/Components/MainCard.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    hirarkiUnitKerja:'',
    paginate:''
})
const cari = ref('')
const paginate = ref(props.paginate)
watch(cari,debounce (value =>{
    console.log('triger');
    router.get(route('hirarki-unit-kerja.index'), {cari:value},{
        preserveState:true,
        preserveScroll:true,
        replace:true
    });
},500));
watch(paginate,value =>{
    router.get(route('hirarki-unit-kerja.index'), {paginate:value},{
        preserveState:true,
        preserveScroll:true,
        replace:true
    });
});
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
        <div class="overflow-x-auto">
            <div class="flex justify-between py-4">
                <div class="justify-start">
                    <button class="btn btn-primary">Tambah</button>
                </div>
                <div class="justify-end">
                    <input
                        v-model="cari"
                        type="text"
                        placeholder="Cari"
                        class="input input-bordered mr-2 w-auto max-w-xs"
                    />
                    <select
                        v-model="paginate"
                        class="select select-bordered w-auto max-w-xs"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <table class="table" aria-describedby="Tabel Alamat Pegawai">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Unit Kerja</th>
                        <th scope="col">Atasan Langsung</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(hirarki,index) in hirarkiUnitKerja.data">
                        <td>{{index+1}}</td>
                        <td>{{hirarki.nama_child}}</td>
                        <td>{{hirarki.nama_parent}}</td>
                        <td>
                            <div class="dropdown dropdown-left">
                                <label tabindex="0" class="btn btn-xs m-1">Aksi</label>
                                <ul tabindex="0" class="w-30 menu dropdown-content rounded-box z-[1] bg-base-100 p-2 shadow">
                                    <li>
                                        <a href="javascript:void(0)">Edit</a>
                                    </li>
                                    <li>
                                        <a>Detail</a>
                                    </li>
                                    <li>
                                        <a>Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :links="hirarkiUnitKerja.links" :paginate="10"/>
        </div>
    </MainCard>
</template>
