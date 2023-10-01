<?php

namespace Tests\RequestFactories;

use App\Models\JenisDiklat;
use App\Models\Pegawai;
use Illuminate\Http\UploadedFile;
use Worksome\RequestFactories\RequestFactory;

class PegawaiRiwayatDiklatRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'jenis_diklat_id' => JenisDiklat::factory(),
            'nama' => fake()->sentence(),
            'tanggal_mulai' => fake()->date(),
            'tanggal_akhir' => fake()->date(),
            'jam_pelajaran' => fake()->numberBetween(1, 30),
            'lokasi' => fake()->city(),
            'penyelenggara' => fake()->company(),
            'no_sertifikat' => fake()->numerify('###########'),
            'tanggal_sertifikat' => fake()->date(),
            'media_sertifikat' => UploadedFile::fake()->image('sertifikat.jpg'),
        ];
    }
}
