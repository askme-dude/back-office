<?php

namespace App\Http\Requests;

use App\Enums\GolonganDarah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|digits:16',
            'nip' => 'required|digits:18',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required',
            'agama_id' => 'required',
            'golongan_darah' => new Enum(GolonganDarah::class),
            'jenis_kawin_id' => 'required',
            'tempat_lahir' => 'required|max:50|regex:/^[\pL\s\-]+$/u',
            'tanggal_lahir' => 'required',
            'email_kantor' => 'required|email:rfc,dns',
            'email_pribadi' => 'nullable|email:rfc,dns',
            'no_telp' => 'required|digits_between:8,13',
            'jenis_pegawai_id' => 'required',
            'status_pegawai_id' => 'required',
            'status_dinas' => 'required',
            'no_kartu_pegawai' => 'nullable|between:7,10|string',
            'tanggal_wafat' => 'nullable|date',
            'tanggal_berhenti' => 'nullable|date',
            'no_bpjs' => 'required|digits:13',
            'no_taspen' => 'nullable|max:50',
            'npwp' => 'nullable|size:16|string',
            'no_enroll' => 'max:50',
            'media_kartu_pegawai' => 'nullable|mimes:jpg,png|max:1024',
            'media_foto_pegawai' => 'nullable|mimes:jpg,png|max:1024',
        ];
    }
}
