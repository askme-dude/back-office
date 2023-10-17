<?php

namespace Database\Factories;

use App\Models\StatusPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StatusPegawaiFactory extends Factory
{
    protected $model = StatusPegawai::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
