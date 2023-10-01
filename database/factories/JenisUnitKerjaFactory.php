<?php

namespace Database\Factories;

use App\Models\JenisUnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisUnitKerjaFactory extends Factory
{
    protected $model = JenisUnitKerja::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
        ];
    }
}
