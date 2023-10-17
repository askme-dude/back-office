<?php

namespace App\Http\Requests;

use App\Models\Penghargaan;
use Illuminate\Foundation\Http\FormRequest;

class SiasnPenghargaanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pnsOrangId' => fake()->numerify('#################'),
            'hargaId' => Penghargaan::factory(),
            'tahun' => fake()->year(),
            'skNomor' => fake()->word(),
            'skDate' => fake()->date(),
            'hargaNama' => fake()->word(),
            'path' => ''
        ];
    }
}
