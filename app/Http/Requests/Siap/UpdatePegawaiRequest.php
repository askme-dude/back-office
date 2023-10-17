<?php

namespace App\Http\Requests\Siap;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'attribute' => ['required', Rule::in(['Alamat', 'hp', 'NIK'])],
            'value' => ['required', 'string'],
        ];
    }
}
