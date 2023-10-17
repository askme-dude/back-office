<?php

namespace Database\Factories;

use App\Models\Agama;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AgamaFactory extends Factory
{
    protected $model = Agama::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'bkn_id' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
