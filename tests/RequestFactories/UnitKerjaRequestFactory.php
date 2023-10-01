<?php

namespace Tests\RequestFactories;

use App\Models\JenisUnitKerja;
use App\Models\UnitKerja;
use Worksome\RequestFactories\RequestFactory;

class UnitKerjaRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'parent_id' => UnitKerja::factory(),
            'jenis_unit_kerja_id' => JenisUnitKerja::factory(),
            'is_active' => fake()->boolean(),
            'nama' => fake()->words(5, true),
            'singkatan' => fake()->word(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
