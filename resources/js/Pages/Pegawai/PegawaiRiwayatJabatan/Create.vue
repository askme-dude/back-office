<script setup>
import MainCard from "@/Components/MainCard.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    pegawai: Array,
});

const people = props.pegawai;

// console.log(people);

let selected = ref("");
let query = ref("");

let filteredPeople = computed(() =>
    query.value === ""
        ? people
        : people.filter((person) =>
              (person.nama_depan + " " + person.nama_belakang)
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(query.value.toLowerCase().replace(/\s+/g, "")),
          ),
);

const form = useForm({
    pegawai_id: selected,
});

const tambahRiwayatJabatan = () => {
    form.post(route("riwayat_jabatan_pegawai.store"));
};
</script>

<template>
    <div>
        <Head title="Tambah Riwayat Jabatan" />
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a>Beranda</a></li>
                <li>Pegawai</li>
                <li>Riwayat Jabatan Pegawai</li>
                <li><span class="text-info">Tambah</span></li>
            </ul>
        </div>
        <MainCard>
            <form @submit.prevent="tambahRiwayatJabatan">
                <div class="form-control">
                    <label class="label justify-start">
                        Nama Pegawai
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <Combobox v-model="form.pegawai_id">
                        <div class="relative mt-1">
                            <div
                                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
                            >
                                <ComboboxInput
                                    class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                                    :displayValue="
                                        (person) =>
                                            person.nama_depan === undefined &&
                                            person.nama_belakang === undefined
                                                ? ''
                                                : person.nama_depan +
                                                  ' ' +
                                                  person.nama_belakang
                                    "
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
                                        v-if="
                                            filteredPeople.length === 0 &&
                                            query !== ''
                                        "
                                        class="relative cursor-default select-none px-4 py-2 text-gray-700"
                                    >
                                        Data tidak ditemukan.
                                    </div>

                                    <ComboboxOption
                                        v-for="person in filteredPeople"
                                        as="template"
                                        :key="person.id"
                                        :value="person"
                                        v-slot="{ selected, active }"
                                    >
                                        <li
                                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                                            :class="{
                                                'bg-teal-600 text-white':
                                                    active,
                                                'text-gray-900': !active,
                                            }"
                                        >
                                            <span
                                                class="block truncate"
                                                :class="{
                                                    'font-medium': selected,
                                                    'font-normal': !selected,
                                                }"
                                            >
                                                {{ person.nama_depan }}
                                                {{ person.nama_belakang }}
                                            </span>
                                            <span
                                                v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                :class="{
                                                    'text-white': active,
                                                    'text-teal-600': !active,
                                                }"
                                            >
                                                <CheckIcon
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </li>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </TransitionRoot>
                        </div>
                    </Combobox>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Jabatan Unit Kerja
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <select
                        v-model="form.jabatan_unit_kerja_id"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    >
                        <option
                            value="1"
                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                        >
                            Staf Easy
                        </option>
                        <option value="2">Staf Normal</option>
                        <option value="3">Staf Hard</option>
                        <option value="4">Staf Insane</option>
                        <option value="5">Leader Beginner</option>
                        <option value="6">Leader Medium</option>
                        <option value="7">Leader Advance</option>
                        <option value="8">Leader Genius</option>
                    </select>
                    <div
                        v-if="form.errors.jabatan_unit_kerja_id"
                        class="text-error"
                    >
                        {{ form.errors.jabatan_unit_kerja_id }}
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Nomor SK
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <input
                        v-model="form.no_sk"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    />
                    <div v-if="form.errors.no_sk" class="text-error">
                        {{ form.errors.no_sk }}
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Nomor Pelantikan
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <input
                        v-model="form.nip"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    />
                    <div v-if="form.errors.nip" class="text-error">
                        {{ form.errors.nip }}
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Tanggal SK
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <input
                        v-model="form.nip"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    />
                    <div v-if="form.errors.nip" class="text-error">
                        {{ form.errors.nip }}
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Tanggal Pelantikan
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <input
                        v-model="form.nip"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    />
                    <div v-if="form.errors.nip" class="text-error">
                        {{ form.errors.nip }}
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label justify-start">
                        Tmt Jabatan
                        <span class="ml-1 text-sm text-red-700">*)</span>
                    </label>
                    <input
                        v-model="form.nip"
                        type="text"
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 shadow-md focus:ring-0"
                    />
                    <div v-if="form.errors.nip" class="text-error">
                        {{ form.errors.nip }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">
                    Simpan
                </button>
            </form>
        </MainCard>
    </div>
</template>
