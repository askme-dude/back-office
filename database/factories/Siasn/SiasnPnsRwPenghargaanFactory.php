<?php

namespace Database\Factories\Siasn;

use App\Models\Penghargaan;
use App\Models\Siasn\SiasnPnsDataUtama;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiasnPnsRwPenghargaanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pnsOrangId' => SiasnPnsDataUtama::factory(),
            'hargaId' => Penghargaan::factory(),
            'tahun' => fake()->year(),
            'skNomor' => fake()->word(),
            'skDate' => fake()->date('d-m-Y'),
            'hargaNama' => fake()->word(),
            'path' => ''
        ];
    }
}
