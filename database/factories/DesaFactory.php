<?php

namespace Database\Factories;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesaFactory extends Factory
{
    protected $model = Desa::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'kecamatan_id' => Kecamatan::factory(),
        ];
    }
}
