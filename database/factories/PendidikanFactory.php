<?php

namespace Database\Factories;

use App\Models\Pendidikan;
use App\Models\TingkatPendidikan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendidikanFactory extends Factory
{
    protected $model = Pendidikan::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'status' => fake()->boolean(),
            'bkn_id' => fake()->numerify('#################'),
            'tingkat_pendidikan_id' => TingkatPendidikan::factory(),
        ];
    }
}
