<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRiwayatPendidikanRequest extends FormRequest
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
        return [
            'id' => ['nullable'],
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'pendidikan_id' => ['required', 'exists:pendidikan,id'],
            'nama_instansi' => ['required', 'max:100'],
            'propinsi_id' => ['required', 'exists:propinsi,id'],
            'kota_id' => ['required', 'exists:kota,id'],
            'alamat' => ['required'],
            'no_ijazah' => ['required', 'max:100'],
            'tanggal_ijazah' => ['required', 'date_format:Y-m-d'],
            'kode_gelar_depan' => ['nullable', 'max:10'],
            'kode_gelar_belakang' => ['nullable', 'max:10'],
            'media_ijazah' => ['required_without:id', 'nullable', 'mimes:pdf,jpg,png,jpeg', 'file', 'max:4096'],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pegawai_id.required' => 'pegawai harus diisi',
            'pegawai_id.exists' => 'pegawai tidak valid',
            'pendidikan_id.required' => 'pendidikan harus diisi',
            'pendidikan_id.exists' => 'pendidikan tidak valid',
            'nama_instansi.required' => 'nama instansi harus diisi',
            'nama_instansi.max' => 'nama instansi maksimal 100 karakter',
            'propinsi_id.required' => 'propinsi harus diisi',
            'propinsi_id.exists' => 'propinsi tidak valid',
            'kota_id.required' => 'kota/kabupaten harus diisi',
            'kota_id.exists' => 'kota tidak valid',
            'alamat.required' => 'alamat harus diisi',
            'tanggal_ijazah.required' => 'tanggal ijazah harus diisi',
            'tanggal_ijazah.date_format' => 'tanggal harus dalam bentuk format yang valid',
            'no_ijazah.required' => ' ijazah harus diisi',
            'no_ijazah.max' => ' nomor ijazah maksimal 100 karakter',
            'kode_gelar_depan.max' => ' gelar depan maksimal 100 karakter',
            'kode_gelar_belakang.max' => 'gelar belakang maksimal 100 karakter',
            'media_ijazah.required_without' => 'file ijazah harus diisi',
            'media_ijazah.mimes' => 'format file ijazah harus pdf/jpg/png/jpeg',
            'media_ijazah.max' => 'ukuran file terlalu besar (maksimal file 4MB)',
        ];
    }
}
