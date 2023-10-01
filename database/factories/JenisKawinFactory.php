<?php

namespace Database\Factories;

use App\Models\JenisKawin;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisKawinFactory extends Factory
{
    protected $model = JenisKawin::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'bkn_id' => $this->faker->word(),
        ];
    }
}
