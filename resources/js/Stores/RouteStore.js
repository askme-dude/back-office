import { defineStore } from "pinia";
import {
    BriefcaseIcon,
    ClipboardIcon,
    HomeIcon,
    UsersIcon,
    MapPinIcon,
    CubeTransparentIcon,
    ArrowRightIcon,
    LockClosedIcon
} from "@heroicons/vue/24/outline/index.js";
import { usePage } from '@inertiajs/vue3';
import { computed } from "vue";
const page = usePage()

const akses = computed(() => page.props.auth.akses)


const useRouteStore = defineStore("route-store", () => {
    const list = [
        {
            icon: HomeIcon,
            label: "Dashboard",
            href: route("dashboard"),
            name: "dashboard",
            permission: "dashboard",
        },
        {
            icon: BriefcaseIcon,
            label: "Unit Kerja",
            href: "/",
            name: "work-unit.*",
            permission: "unit_kerja",
            children: [
                {
                    icon: ArrowRightIcon,
                    label: "Test",
                    href:"#",
                    permission: "test",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Hirarki Unit Kerja",
                    href:route('hirarki-unit-kerja.index'),
                    permission: "hirarki_unit_kerja_list",
                },
            ],
        },
        {
            icon: UsersIcon,
            label: "Pegawai",
            href: "/",
            name: "work-unit.*",
            permission: "pegawai",
            children: [
                {
                    icon: ArrowRightIcon,
                    label: "Alamat Pegawai",
                    href: route("alamat.index"),
                    permission: "alamat_pegawai_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Riwayat Diklat",
                    href: route("riwayat-diklat.index"),
                    permission: "riwayat_diklat_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Tmt Gaji",
                    href: route("tmt-gaji.index"),
                    permission: "tmt_gaji_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Riwayat Pendidikan",
                    href: route("riwayat-pendidikan.index"),
                    permission: "riwayat_pendidikan_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Anak",
                    href: route("anak.index"),
                    permission: "anak_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Suami Istri",
                    href: route("suami-istri.index"),
                    permission: "suami_istri_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Saldo Cuti",
                    href: route("saldo-cuti.index"),
                    permission: "saldo_cuti_list",
                },
            ],
        },
        {
            icon: CubeTransparentIcon,
            label: "Master",
            href: "/",
            name: "work-unit.*",
            permission: "master",
            children: [
                {
                    icon: ArrowRightIcon,
                    label: "Tunjangan Kinerja",
                    href: route("tukin.index"),
                    permission: "tunjangan_kinerja_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Jabatan Tukin",
                    href: route("jabatan-tukin.index"),
                    permission: "jabatan_tukin_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Jabatan Unit Kerja",
                    href: route("jabatan-unit-kerja.index"),
                    permission: "jabatan_unit_kerja_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Gaji",
                    href: route("gaji.index"),
                    permission: "gaji_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Uang Makan",
                    href: route("umak.index"),
                    permission: "uang_makan_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Hari Libur",
                    href: route("hari-libur.index"),
                    permission: "hari_libur_list",
                },
            ],
        },
        {
            icon: LockClosedIcon,
            label: "Pengaturan",
            href: "/",
            name: "work-unit.*",
            permission: "pengaturan",
            children: [
                {
                    icon: ArrowRightIcon,
                    label: "Role",
                    href: route("role.index"),
                    permission: "role_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Pengguna",
                    href: route("user.index"),
                    permission: "pengguna_list",
                },
                {
                    icon: ArrowRightIcon,
                    label: "Hak Akses",
                    href: route("permission.index"),
                    permission: "hak_akses_list",
                },
            ],
        },
    ];

    function checkPermission(strPermission, permission) {
        console.log('check permission:' + strPermission);
        console.log(permission[strPermission]);

        if (permission[strPermission]){
            return true;
        }else{
            return false;
        }

    }

    const isHasAccess = (item,permission) => {
        if (!item.hasOwnProperty("showIf")) {
            if (checkPermission(item.permission,permission)){
                return true;
            }else{
                return false;
            }
        }
        return item.showIf();
    };

    return { list, isHasAccess };
});

export default useRouteStore;
