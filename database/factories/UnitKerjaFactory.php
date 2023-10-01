<?php

namespace Database\Factories;

use App\Models\JenisUnitKerja;
use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitKerjaFactory extends Factory
{
    protected $model = UnitKerja::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->words(4, true),
            'jenis_unit_kerja_id' => JenisUnitKerja::factory(),
            'singkatan' => fake()->word(),
            'keterangan' => fake()->words(3, true),
        ];
    }
}
