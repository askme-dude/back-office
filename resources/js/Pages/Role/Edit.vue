<script setup>
  import MainCard from "@/Components/MainCard.vue";
  import TextInput from "@/Components/TextInput.vue";
  import Checkbox from "@/Components/Checkbox.vue";
  import InputLabel from "@/Components/InputLabel.vue";
  import InputError from "@/Components/InputError.vue";
  import { useForm, usePage } from "@inertiajs/vue3";
  import { ref } from "vue";
  import Swal from "sweetalert2";

  const page = usePage();

  const { permissions } = defineProps(['permissions']);

  const checkAll = ref(false);

  const form = useForm({
    name: "",
    guard_name: "-",
    hak_akses: permissions.map(permission => permission.id),
    hak_akses_value: permissions.map(() => false),
  });

  const goBack = () => {
    window.history.back();
  };

  const toggleAllPermissions = () => {
    console.log('OK');
    if (checkAll.value) {
      form.hak_akses_value = permissions.map(() => true);
      form.hak_akses = permissions.map(permission => permission.id);
    } else {
      form.hak_akses_value = permissions.map(() => false);
      form.hak_akses = [];
    }
  };

  const handlePermissionChange = (index) => {
        form.hak_akses_value[index] = !form.hak_akses_value[index];
    };

  const submitForm = () => {

    let tot = 0;
    for (let index = 0; index < form.hak_akses_value.length; index++) {
        const element = form.hak_akses_value[index];
        if (element === true) {
            tot = 1;
            break;
        }
    };

    if (tot === 0) {

        Swal.fire({
        icon: 'warning',
        title: 'Oops!',
        text: 'Anda harus memilih setidaknya satu hak akses.',
        });
        return; // Berhenti jika tidak ada hak akses yang dipilih
    };

    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Simpan Role Baru?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Ya",
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route("role.store"), {
                onSuccess: (response) => {
                    Swal.fire(
                        "Tersimpan!",
                        "Role baru berhasil disimpan.",
                        "success",
                    );
                    // form.reset(); // ini untuk reset inputan tanpa merefresh halaman atau tanpa balik ke index
                    router.get(route("role.index"));
                },
                onError: (errors) => {
                    if (errors.query) {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Simpan Role gagal",
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
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Tambah Role Baru</h2>
      </template>

      <div class="py-12">
        <div class="mx-auto max-w-7xl">
          <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200 bg-white p-6">
              <form @submit.prevent="submitForm">
                <div class="my-6">
                  <InputLabel for="name" value="Nama Role :" />
                  <TextInput
                    id="name"
                    name="name"
                    type="text"
                    maxlength="50"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                  />
                  <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                  <label>
                    <input type="checkbox" v-model="checkAll" @change="toggleAllPermissions" />
                    Check All
                  </label>
                </div>

                <div>
                  <InputLabel for="hak_akses" value="Hak Akses :" />

                  <div v-for="(permission, index)  in permissions" :key="permission.id">
                    <Checkbox
                      v-model="form.hak_akses"
                      :value="permission.id"
                      :checked="form.hak_akses_value[index] == true"
                      class="flex-column gap-2"
                      @change="handlePermissionChange(index)"
                    >
                    </Checkbox>
                    {{ permission.name }}
                  </div>

                  <InputError class="mt-2" :message="form.errors.hak_akses" />
                </div>

                <div class="flex justify-end">
                  <button class="btn btn-error mx-2" @click="goBack">Batal</button>
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

