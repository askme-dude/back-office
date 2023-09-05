<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TukinStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'grade' => ['required', 'integer', 'min:1', 'max:9999'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999999999'],
            'keterangan' => [],
        ];
    }

    public function messages()
    {
        return [
            'grade.required'=>'data grade harus diisi!',
            'grade.integer'=>'data grade harus bilangan bulat positif!',
            'nominal.required'=>'data nominal harus diisi!',
            'nominal.integer'=>'data nominal harus bilangan bulat positif!',
        ];
    }
}
