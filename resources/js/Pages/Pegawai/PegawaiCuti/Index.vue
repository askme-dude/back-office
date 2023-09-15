<script setup>

import MainCard from "@/Components/MainCard.vue";
import { router, useForm } from "@inertiajs/vue3";
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import { ref, computed, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    title:'',
    jenis_cuti:''
})

const form = useForm({
    date:Array,
    jenis_cuti_id:'',
    alasan:'',
    no_telp:'',
    alamat:'',
    media_cuti:''

})


const jenis_cuti = props.jenis_cuti
const selected = ref ('')
let query = ref('')

let filteredJenisCuti = computed(() =>
    query.value === ''
        ? jenis_cuti
        : jenis_cuti.filter((jenis) =>
            jenis.jenis
                .toLowerCase()
                .replace(/\s+/g, '')
                .includes(query.value.toLowerCase().replace(/\s+/g, ''))
        )
)
watch(()=>selected.value,(value)=>{
    form.jenis_cuti_id = value.id
});
</script>

<template>
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>Beranda</a></li>
            <li>Pegawai</li>
            <li><span class="text-info">{{title}}</span></li>
        </ul>
    </div>
<MainCard>
    <div class="w-full p-6 m-auto lg:max-w-xl">
        <h2 class="text-2xl font-semibold text-center text-gray-700">{{title}}</h2>
        <form class="space-y-4" >
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Tanggal Cuti</span>
                </label>
                <date-picker
                    class="w-full"
                    v-model:value="form.date"
                    format="DD-MM-YYYY"
                    type="date"
                    range
                    placeholder="Tanggal pengajuan cuti"
                ></date-picker>
                <label class="label">
                    <span v-if="form.errors.tanggal_akhir" class="label-text-alt text-error">{{form.errors.tanggal_akhir}}</span>
                </label>
            </div>

            <div class="form-control">
                <div class="w-full">
                    <label class="label">
                        <span class="label-text">Jenis Cuti</span>
                    </label>
                    <Combobox v-model="selected">
                        <div class="relative mt-1">
                            <div
                                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
                            >
                                <ComboboxInput
                                    class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                                    :displayValue="(jenis) => jenis.jenis"
                                    @change="query = $event.target.value"
                                />
                                <ComboboxButton
                                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                                >
                                    <ChevronUpDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </ComboboxButton>
                            </div>
                            <TransitionRoot
                                leave="transition ease-in duration-100"
                                leaveFrom="opacity-100"
                                leaveTo="opacity-0"
                                @after-leave="query = ''"
                            >
                                <ComboboxOptions
                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                >
                                    <div
                                        v-if="filteredJenisCuti.length === 0 && query !== ''"
                                        class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                    >
                                        Nothing found.
                                    </div>

                                    <ComboboxOption
                                        v-for="jenis in filteredJenisCuti"
                                        as="template"
                                        :key="jenis.id"
                                        :value="jenis"
                                        v-slot="{ selected, active }"
                                    >
                                        <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{'bg-teal-600 text-white': active, 'text-gray-900': !active,}">
                <span
                    class="block truncate"
                    :class="{ 'font-medium': selected, 'font-normal': !selected }"
                >
                  {{ jenis.jenis }}
                </span>
                                            <span
                                                v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                :class="{ 'text-white': active, 'text-teal-600': !active }"
                                            >
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
                                        </li>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </TransitionRoot>
                        </div>
                    </Combobox>
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Alasan Cuti</span>
                </label>
                <textarea v-model="form.alasan" class="textarea textarea-bordered h-24" placeholder="Alasan"></textarea>
                <label class="label">
                    <span v-if="form.errors.alasan" class="label-text-alt text-error">{{form.errors.alasan}}</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Alamat Cuti</span>
                </label>
                <textarea v-model="form.alamat_cuti" class="textarea textarea-bordered h-24" placeholder="Alamat selama cuti"></textarea>
                <label class="label">
                    <span v-if="form.errors.alamat_cuti" class="label-text-alt text-error">{{form.errors.alamat_cuti}}</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">No Telepon</span>
                </label>
                <input v-model="form.no_telepon_cuti"  type="number" placeholder="Masukkan no telepon"  class="input input-bordered" />
                <label class="label">
                    <span v-if="form.errors.no_telepon_cuti" class="label-text-alt text-error">{{form.errors.no_telepon_cuti}}</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">File Pendukung</span>
                </label>
                <input accept=".pdf" @input="form.media_cuti = $event.target.files[0]"   type="file" placeholder="Masukkan file ijazah"  class="file-input file-input-bordered" />
                <label class="label">
                    <span v-if="form.errors.media_cuti" class="label-text-alt text-error">{{form.errors.media_cuti}}</span>
                </label>
            </div>
            <div class="flex justify-end">
                <a class="btn btn-error mx-2" @click="console.log('oke')">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</MainCard>
</template>

