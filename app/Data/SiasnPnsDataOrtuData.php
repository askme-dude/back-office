<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SiasnPnsDataOrtuData extends Data
{
    public function __construct(
        public string      $idPns,
        public string      $ayahId,
        public string      $ayahNama,
        public string|null $ayahTempatLahir,
        public string|null $ayahTglLahir,
        public string|null $ayahAktaMeninggal,
        public string|null $ayahTglMeninggal,
        public string|null $ayahJenisKelamin,
        public string|null $ayahJenisAnak,
        public string      $ibuId,
        public string      $ibuNama,
        public string|null $ibuTempatLahir,
        public string|null $ibuTglLahir,
        public string|null $ibuAktaMeninggal,
        public string|null $ibuTglMeninggal,
        public string|null $ibuJenisKelamin,
        public string|null $ibuJenisAnak,
    )
    {
    }

    public static function fromResponse($payload): static
    {
        return new self(
            idPns: $payload['idPns'],
            ayahId: $payload['ayah']['id'],
            ayahNama: $payload['ayah']['nama'],
            ayahTempatLahir: $payload['ayah']['tempatLahir'],
            ayahTglLahir: $payload['ayah']['tglLahir'],
            ayahAktaMeninggal: $payload['ayah']['aktaMeninggal'],
            ayahTglMeninggal: $payload['ayah']['tglMeninggal'],
            ayahJenisKelamin: $payload['ayah']['jenisKelamin'],
            ayahJenisAnak: $payload['ayah']['jenisAnak'],
            ibuId: $payload['ibu']['id'],
            ibuNama: $payload['ibu']['nama'],
            ibuTempatLahir: $payload['ibu']['tempatLahir'],
            ibuTglLahir: $payload['ibu']['tglLahir'],
            ibuAktaMeninggal: $payload['ibu']['aktaMeninggal'],
            ibuTglMeninggal: $payload['ibu']['tglMeninggal'],
            ibuJenisKelamin: $payload['ibu']['jenisKelamin'],
            ibuJenisAnak: $payload['ibu']['jenisAnak']
        );
    }
}
