<?php

namespace Database\Factories;

use App\Models\Kecamatan;
use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\Factory;

class KecamatanFactory extends Factory
{
    protected $model = Kecamatan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'kota_id' => Kota::factory(),
        ];
    }
}
