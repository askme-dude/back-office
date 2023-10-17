<script setup>
import MainCard from "@/Components/MainCard.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router, usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import Swal from "sweetalert2";

const { role } = defineProps(['role']);


const form = useForm({
    name: "",
    email: "",
    password: "",
    role_id:"",
});


const goBack = () => {
    window.history.back();
};

const submit = () => {
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Simpan data Data Pengguna",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Ya",
    }).then((result) => {
        if (result.isConfirmed) {

            form.post(route("user.store"), {
                onSuccess: (response) => {
                    Swal.fire(
                        "Tersimpan!",
                        "Pengguna Baru berhasil disimpan.",
                        "success",
                    );

                    router.get(route("user.index"));
                },
                onError: (errors) => {
                    if (errors.query) {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Simpan pengguna baru gagal",
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                },
            });
        }
    });
};
</script>

<template>
    <MainCard>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="/user">Pengguna</a></li>

            </ul>
        </div>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                <!-- Tambah Tunjangan Kinerja Jabatan -->
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800">
                            Tambah Penguna
                        </h2>
                        <form @submit.prevent="submit">
                            <div class="my-6">
                                <InputLabel
                                    for="role_id"
                                    value="Pilih Role Pengguna"
                                />
                                <select
                                    v-model="role_id"
                                    id="role_id"
                                    name="role_id"
                                    required
                                >
                                    <option value="-1" disabled selected>
                                        Pilih Role Pengguna
                                    </option>
                                    <option
                                        v-for="data in role"
                                        :key="data.id"
                                        :value="data.id"
                                    >
                                        {{ data.name }}
                                    </option>
                                </select>

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.role_id"
                                />
                            </div>


                            <div class="my-6">
                                <InputLabel for="name" value="Input Nama Penguna" />

                                <TextInput
                                    v-model="form.name"
                                    id="name"
                                    name="name"
                                    type="text"
                                    maxlength="50"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    autocomplete=""
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="flex justify-end">
                                <button
                                    class="btn btn-error mx-2"
                                    @click.prevent="goBack"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary mx-2"
                                    :class="{ 'form.processing': isProcessing }"
                                >
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </MainCard>
</template>
