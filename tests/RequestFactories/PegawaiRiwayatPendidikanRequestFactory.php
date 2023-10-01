<?php

namespace Tests\RequestFactories;

use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\Pendidikan;
use App\Models\Propinsi;
use Illuminate\Http\UploadedFile;
use Worksome\RequestFactories\RequestFactory;

class PegawaiRiwayatPendidikanRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'pendidikan_id' => Pendidikan::factory(),
            'nama_instansi' => fake()->company(),
            'propinsi_id' => Propinsi::factory(),
            'kota_id' => Kota::factory(),
            'alamat' => fake()->address(),
            'no_ijazah' => fake()->numerify('#########'),
            'tanggal_ijazah' => fake()->dateTimeThisYear()->format('Y-m-d'),
            'kode_gelar_depan' => fake()->randomLetter(),
            'kode_gelar_belakang' => fake()->randomLetter(),
            'media_ijazah' => UploadedFile::fake()->image('ijazah.pdf'),
        ];
    }
}
