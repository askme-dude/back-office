<?php

namespace Database\Factories\Siasn\Referensi;

use App\Models\Siasn\Referensi\RefDokumen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RefDokumenFactory extends Factory
{
    protected $model = RefDokumen::class;

    public function definition(): array
    {
        return [
            'layananId' => $this->faker->randomNumber(),
            'layananNama' => $this->faker->word(),
            'subLayananId' => $this->faker->randomNumber(),
            'subLayananNama' => $this->faker->word(),
            'detailLayananId' => $this->faker->randomNumber(),
            'detailLayananNama' => $this->faker->word(),
            'document' => $this->faker->word(),
            'jenisDokumen' => $this->faker->word(),
            'fileType' => $this->faker->word(),
            'linkProses' => $this->faker->word(),
            'mandatory' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
