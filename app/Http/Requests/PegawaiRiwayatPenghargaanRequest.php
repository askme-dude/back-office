<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRiwayatPenghargaanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pegawai_id' => ['required'],
            'penghargaan_id' => ['required'],
            'no_sk' => ['required', 'string'],
            'tanggal_sk' => ['required', 'date'],
            'tahun' => ['required', 'digits:4'],
            'media_sk' => ['nullable', 'mimes:jpg,png,jpeg,pdf'],
        ];
    }
}
