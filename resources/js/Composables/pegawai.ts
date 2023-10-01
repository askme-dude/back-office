import { router } from "@inertiajs/vue3";

export function useFetchPegawaiByNama(
    url: string,
    nama: string,
    label: string = "pegawai",
) {
    router.get(
        url,
        { filter: { nama } },
        {
            only: [label],
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
}
