<?php

namespace Database\Factories;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\PegawaiAlamat;
use App\Models\Propinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiAlamatFactory extends Factory
{
    protected $model = PegawaiAlamat::class;

    public function definition(): array
    {
        return [
            'tipe_alamat' => fake()->randomElement(['Domisili', 'Asal']),
            'kode_pos' => fake()->numerify('#####'),
            'alamat' => fake()->address(),
            'pegawai_id' => Pegawai::factory(),
            'propinsi_id' => Propinsi::factory(),
            'kota_id' => Kota::factory(),
            'kecamatan_id' => Kecamatan::factory(),
            'desa_id' => Desa::factory(),
        ];
    }
}
