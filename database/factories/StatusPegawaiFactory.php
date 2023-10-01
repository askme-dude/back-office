<?php

namespace Database\Factories;

use App\Models\StatusPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusPegawaiFactory extends Factory
{
    protected $model = StatusPegawai::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
        ];
    }
}
