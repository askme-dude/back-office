<?php

namespace Tests\RequestFactories;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\Propinsi;
use Worksome\RequestFactories\RequestFactory;

class PegawaiAlamatRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'tipe_alamat' => fake()->randomElement(['Domisili', 'Asal']),
            'propinsi_id' => Propinsi::factory(),
            'kota_id' => Kota::factory(),
            'kecamatan_id' => Kecamatan::factory(),
            'desa_id' => Desa::factory(),
            'kode_pos' => fake()->numerify('#####'),
            'alamat' => fake()->address(),
        ];
    }
}
