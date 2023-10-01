<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitKerjaRequest extends FormRequest
{
    public function rules(): array
    {
        $unit_id = request('unit_kerja');

        return [
            'parent_id' => $unit_id ? ['nullable', 'integer', Rule::notIn($unit_id)] : ['nullable', 'integer'],
            'jenis_unit_kerja_id' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'nama' => ['required'],
            'singkatan' => ['nullable'],
            'keterangan' => ['nullable'],
        ];
    }
}
