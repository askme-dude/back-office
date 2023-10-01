<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PegawaiAlamatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return
            [
                'pegawai_id' => ['required', 'exists:pegawai,id'],
                'tipe_alamat' => ['required', Rule::in(["Domisili", "Asal"])],
                'propinsi_id' => ['required', 'max:10', 'exists:propinsi,id'],
                'kota_id' => ['required', 'max:20', 'exists:kota,id'],
                'kecamatan_id' => ['required', 'max:20', 'exists:kecamatan,id'],
                'desa_id' => ['required', 'max:20', 'exists:desa,id'],
                'kode_pos' => ['required', 'max:5'],
                'alamat' => ['required', 'string'],
            ];
    }

    public function messages()
    {
        return [
            'pegawai_id.required' => 'pegawai harus diisi',
            'pegawai_id.max' => 'pegawai tidak valid',
            'pegawai_id.exists' => 'pegawai tidak valid',
            'tipe_alamat.required' => 'tipe alamat harus diisi',
            'tipe_alamat.in' => 'tipe alamat tidak valid',
            'propinsi_id.required' => 'propinsi harus diisi',
            'propinsi_id.max' => 'propinsi tidak valid',
            'propinsi_id.exists' => 'propinsi tidak valid',
            'kota_id.required' => 'kota/kabupaten harus diisi',
            'kota_id.max' => 'kota/kabupaten tidak valid',
            'kota_id.exists' => 'kota tidak valid',
            'kecamatan_id.required' => 'kecamatan harus diisi',
            'kecamatan_id.max' => 'kecamatan tidak valid',
            'kecamatan_id.exists' => 'kecamatan tidak valid',
            'desa_id.required' => 'desa harus diisi',
            'desa_id.max' => 'desa tidak valid',
            'desa_id.exists' => 'desa tidak valids',
            'kode_pos.required' => 'kode pos harus diisi',
            'kode_pos.max' => 'kode pos tidak valid',
            'alamat.required' => 'alamat harus diisi',
        ];
    }
}
