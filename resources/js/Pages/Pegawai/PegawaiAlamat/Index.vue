<script setup>
import MainCard from "@/Components/MainCard.vue";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Swal from "sweetalert2";

const props = defineProps({
    pegawaiAlamat:'',
    paginate:''
})
const tambahAlamat = ()=>{
    router.get(route('alamat.create'));
}
const toEdit = (id)=>{
    router.get(route('alamat.edit',id))
}
const toDelete = (id)=>{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "hapus data pegawai",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('alamat.destroy',id),{
                onSuccess:(response)=>{
                    Swal.fire(
                        'Dihapus!',
                        'data pegawai berhasil dihapus.',
                        'success'
                    )
                },
                onError:(errors)=>{
                    if(errors.query){
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'alamat pegawai gagal dihapus',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })

        }
    })

}

const cari = ref('')
const paginate = ref(props.paginate)
watch(cari,debounce (value =>{
    console.log('triger');
        router.get(route('alamat.index'), {cari:value},{
            preserveState:true,
            preserveScroll:true,
            replace:true
    });
},500));
watch(paginate,value =>{
    router.get(route('alamat.index'), {paginate:value},{
        preserveState:true,
        preserveScroll:true,
        replace:true
    });
});
const pegawaiAlamatDetail = ref([])
const showDetail=(id)=>{
    router.get(route('alamat.index'),{pegawai_alamat_id:id},{

        preserveState:true,
        preserveScroll:true,
        replace:true,
        only:[
            'pegawaiAlamatDetail'
        ],
        onSuccess:(response)=>{
            pegawaiAlamatDetail.value = response.props.pegawaiAlamatDetail
        }
    })
    modal_alamat.showModal()
}
</script>
<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li><span class="text-info">Alamat</span></li>
        </ul>
    </div>
    <MainCard>
        <div class="overflow-x-auto">
            <div class="py-4 flex justify-between">
                <div class="justify-start">
                    <button class="btn btn-primary" @click="tambahAlamat">Tambah</button>
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
            <table class="table" aria-describedby="Tabel Alamat Pegawai">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tipe</th>
                    <th scope="col">Propinsi</th>
                    <th scope="col">Kota/Kabupaten</th>
                    <th scope="col">Kecamatan</th>
                    <th scope="col">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover" v-for="(alamat,index) in pegawaiAlamat.data">
                    <th scope="col">{{index+1}}</th>
                    <th scope="col">{{alamat.nama_depan +' '+ alamat.nama_belakang}}</th>
                    <th scope="col" v-if="alamat.tipe_alamat === 'D'">Domisili</th>
                    <th scope="col" v-else-if="alamat.tipe_alamat === 'K'">Kampung</th>
                    <td>{{alamat.nama_propinsi}}</td>
                    <td>{{alamat.nama_kota}}</td>
                    <td>{{alamat.nama_kecamatan}}</td>
                    <td>
                        <div class="dropdown dropdown-left">
                            <label tabindex="0" class="btn btn-xs m-1">Aksi</label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-30">
                                <li><a href="javascript:void(0)" @click="toEdit(alamat.id)">Edit</a></li>
                                <li><a @click="showDetail(alamat.id)">Detail</a></li>
                                <li><a href="javascript:void(0)" @click="toDelete(alamat.id)">Hapus</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
                <div class="join flex justify-end">
                    <Component
                    :is="link.url?'a':'span'"
                    v-for="link in pegawaiAlamat.links"
                    :href="link.url+'&paginate='+paginate"
                    v-html="link.label"
                    class="join-item btn btn-xs"
                    :class="{'btn-disabled': !link.url, 'btn-active':link.active}"
                    />
                </div>
        </div>
    </MainCard>
    <!-- Open the modal using ID.showModal() method -->
    <dialog id="modal_alamat" class="modal">
        <form method="dialog" class="modal-box">
            <div class="overflow-x-auto">
               <table class="w-11/12">
                   <caption class="font-bold text-lg mb-4">Detail Pegawai</caption>
                   <thead>
                   <tr>
                       <th></th>
                       <th></th>
                   </tr>
                   </thead>
                   <tr>
                       <td class="">Nama Pegawai</td>
                       <td v-if="pegawaiAlamatDetail"  v-html="pegawaiAlamatDetail.nama_depan +' ' + pegawaiAlamatDetail.nama_belakang"></td>
                   </tr>
                   <tr>
                       <td>Tipe Alamat</td>
                       <td v-if="pegawaiAlamatDetail.tipe_alamat === 'D'">Domisili</td>
                       <td v-if="pegawaiAlamatDetail.tipe_alamat === 'K'">Kampung</td>
                   </tr>
                   <tr>
                       <td>Propinsi</td>
                       <td v-html="pegawaiAlamatDetail.nama_propinsi"></td>
                   </tr>
                   <tr>
                       <td>Kota/Kabupaten</td>
                       <td v-html="pegawaiAlamatDetail.nama_kota"></td>
                   </tr>
                   <tr>
                       <td>Kecamatan</td>
                       <td v-html="pegawaiAlamatDetail.nama_kecamatan"></td>
                   </tr>
                   <tr>
                       <td>Desa</td>
                       <td v-html="pegawaiAlamatDetail.nama_desa"></td>
                   </tr>
                   <tr>
                       <td>Kode Pos</td>
                       <td v-html="pegawaiAlamatDetail.kode_pos"></td>
                   </tr>
                   <tr>
                       <td>Alamat</td>
                       <td v-html="pegawaiAlamatDetail.alamat"></td>
                   </tr>
               </table>
            </div>
            <div class="modal-action">
                <button class="btn btn-sm btn-primary">Tutup</button>
            </div>
        </form>
    </dialog>
</template>
