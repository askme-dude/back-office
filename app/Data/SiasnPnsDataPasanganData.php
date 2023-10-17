<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SiasnPnsDataPasanganData extends Data
{
    public function __construct(
        public string      $idPns,
        public string      $orangId,
        public string|null $ayahId,
        public string|null $ibuId,
        public string      $nama,
        public string|null $namaKtp,
        public string|null $gelarDepan,
        public string|null $gelarBlk,
        public string|null $tempatLahir,
        public string|null $tglLahir,
        public string|null $aktaMeninggal,
        public string|null $tglMeninggal,
        public string|null $jenisKelamin,
        public string|null $jenisAnak,
        public string|null $statusHidup,
        public string|null $jenisKawinId,
        public string|null $karisKarsu,
        public string|null $statusNikah,
        public string|null $dataPernikahanId,
        public string|null $tglMenikah,
        public string|null $aktaMenikah,
        public string|null $tglCerai,
        public string|null $aktaCerai,
        public string|null $posisi,
        public string|null $status,
        public string|null $isPns,
        public string|null $noSkPensiun,
    )
    {
    }

    public static function fromResponse($idPns, $payloads): static
    {
        return new self(
            idPns: $idPns,
            orangId: $payloads['orang']['id'],
            ayahId: $payloads['orang']['ayahId'],
            ibuId: $payloads['orang']['ibuId'],
            nama: $payloads['orang']['nama'],
            namaKtp: $payloads['orang']['namaKtp'],
            gelarDepan: $payloads['orang']['gelarDepan'],
            gelarBlk: $payloads['orang']['gelarBlk'],
            tempatLahir: $payloads['orang']['tempatLahir'],
            tglLahir: $payloads['orang']['tglLahir'],
            aktaMeninggal: $payloads['orang']['aktaMeninggal'],
            tglMeninggal: $payloads['orang']['tglMeninggal'],
            jenisKelamin: $payloads['orang']['jenisKelamin'],
            jenisAnak: $payloads['orang']['jenisAnak'],
            statusHidup: $payloads['orang']['StatusHidup'],
            jenisKawinId: $payloads['orang']['JenisKawinId'],
            karisKarsu: $payloads['orang']['karisKarsu'],
            statusNikah: $payloads['statusNikah'],
            dataPernikahanId: $payloads['dataPernikahan']['id'],
            tglMenikah: $payloads['dataPernikahan']['tgglMenikah'],
            aktaMenikah: $payloads['dataPernikahan']['aktaMenikah'],
            tglCerai: $payloads['dataPernikahan']['tgglCerai'],
            aktaCerai: $payloads['dataPernikahan']['aktaCerai'],
            posisi: $payloads['dataPernikahan']['posisi'],
            status: $payloads['dataPernikahan']['status'],
            isPns: $payloads['dataPernikahan']['isPns'],
            noSkPensiun: $payloads['dataPernikahan']['noSkPensiun'],
        );
    }
}
