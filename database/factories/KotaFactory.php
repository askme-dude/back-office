<?php

namespace Database\Factories;

use App\Models\Kota;
use App\Models\Propinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

class KotaFactory extends Factory
{
    protected $model = Kota::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->city(),
            'propinsi_id' => Propinsi::factory(),
        ];
    }
}
