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
                    label: "Status Pegawai",
                    href: route("pegawai-status.index")
                },
                {
                    icon: ClipboardIcon,
                    label: "Unit Kerja",
                    href: route("unit-kerja.index")
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
