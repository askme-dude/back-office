<?php

namespace Database\Factories;

use App\Models\TingkatPendidikan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TingkatPendidikanFactory extends Factory
{
    protected $model = TingkatPendidikan::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
            'group_tk_pend_nm' => fake()->word(),
            'bkn_id' => fake()->word(),
        ];
    }
}
