<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiRiwayatPenghargaanFactory extends Factory
{
    protected $model = PegawaiRiwayatPenghargaan::class;

    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'penghargaan_id' => Penghargaan::factory(),
            'bkn_id' => fake()->word(),
            'no_sk' => fake()->numerify('######'),
            'tanggal_sk' => fake()->date(),
            'tahun' => fake()->year(),
        ];
    }
}
