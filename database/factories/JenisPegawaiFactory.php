<?php

namespace Database\Factories;

use App\Models\JenisPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisPegawaiFactory extends Factory
{
    protected $model = JenisPegawai::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'bkn_id' => $this->faker->word(),
        ];
    }
}
