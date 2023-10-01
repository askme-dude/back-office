<?php

namespace Database\Factories;

use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPendidikan;
use App\Models\Pendidikan;
use App\Models\Propinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiRiwayatPendidikanFactory extends Factory
{
    protected $model = PegawaiRiwayatPendidikan::class;

    public function definition(): array
    {
        return [
            'nama_instansi' => fake()->company(),
            'propinsi_id' => Propinsi::factory(),
            'kota_id' => Kota::factory(),
            'alamat' => fake()->address(),
            'no_ijazah' => fake()->numerify('#######'),
            'tanggal_ijazah' => fake()->dateTimeThisYear()->format('Y-m-d'),
            'kode_gelar_depan' => fake()->word(),
            'kode_gelar_belakang' => fake()->word(),
            'pegawai_id' => Pegawai::factory(),
            'pendidikan_id' => Pendidikan::factory(),
        ];
    }
}
