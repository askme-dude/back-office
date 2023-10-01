<?php

namespace Database\Factories;

use App\Models\JenisDiklat;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisDiklatFactory extends Factory
{
    protected $model = JenisDiklat::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
            'jenis_kursus_sertifikat' => fake()->word(),
            'bkn_id' => fake()->numberBetween(1, 10),
        ];
    }
}
