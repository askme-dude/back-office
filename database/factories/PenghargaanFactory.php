<?php

namespace Database\Factories;

use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenghargaanFactory extends Factory
{
    protected $model = Penghargaan::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
        ];
    }
}
