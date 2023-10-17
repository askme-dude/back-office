<?php

namespace Tests\RequestFactories;

use App\Models\Pegawai;
use App\Models\Penghargaan;
use Illuminate\Http\UploadedFile;
use Worksome\RequestFactories\RequestFactory;

class PegawaiRiwayatPenghargaanRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'penghargaan_id' => Penghargaan::factory(),
            'no_sk' => fake()->numerify('######'),
            'tanggal_sk' => fake()->date(),
            'tahun' => fake()->year(),
            'media_sk' => UploadedFile::fake()->image('sk.pdf'),
        ];
    }
}
