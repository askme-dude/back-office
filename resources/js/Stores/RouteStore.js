import { defineStore } from "pinia";
import {
    BriefcaseIcon,
    ClipboardIcon,
    HomeIcon,
    UsersIcon,
    MapPinIcon,
    CubeTransparentIcon
} from "@heroicons/vue/24/outline/index.js";

const useRouteStore = defineStore("route-store", () => {
    const list = [
        {
            icon: HomeIcon,
            label: "Dashboard",
            href: route("dashboard"),
            name: "dashboard",
        },
        {
            icon: BriefcaseIcon,
            label: "Unit Kerja",
            href: "/",
            name: "work-unit.*",
            children: [
                {
                    icon: ClipboardIcon,
                    label: "Test",
                    href:"#"
                },
                {
                    icon: CubeTransparentIcon,
                    label: "Hirarki Unit Kerja",
                    href:route('hirarki-unit-kerja.index')
                },
            ],
        },
        {
            icon: UsersIcon,
            label: "Pegawai",
            href: "/",
            name: "work-unit.*",
            children: [
                {
                    icon: MapPinIcon,
                    label: "Alamat Pegawai",
                    href: route("alamat.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Riwayat Diklat",
                    href: route("riwayat-diklat.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Tmt Gaji",
                    href: route("tmt-gaji.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Riwayat Pendidikan",
                    href: route("riwayat-pendidikan.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Anak",
                    href: route("anak.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Suami Istri",
                    href: route("suami-istri.index")
                },
                {
                    icon: MapPinIcon,
                    label: "Saldo PegawaiCuti",
                    href: route("saldo-cuti.index")
                },
            ],
        },
        {
            icon: BriefcaseIcon,
            label: "Master",
            href: "/",
            name: "work-unit.*",
            children: [
                {
                    icon: ClipboardIcon,
                    label: "Tunjangan Kinerja",
                    href: route("tukin.index")
                },
                {
                    icon: ClipboardIcon,
                    label: "Uang Makan",
                    href: route("umak.index")
                },
                {
                    icon: ClipboardIcon,
                    label: "Hari Libur",
                    href: route("hari-libur.index")
                },
            ],
        },
    ];

    const isHasAccess = (item) => {
        if (!item.hasOwnProperty("showIf")) {
            return true;
        }

        return item.showIf();
    };

    return { list, isHasAccess };
});

export default useRouteStore;
