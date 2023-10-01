<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRiwayatDiklatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jenis_diklat_id' => ['required', 'exists:jenis_diklat,id'],
            'nama' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date_format:Y-m-d', 'lte:tanggal_akhir'],
            'tanggal_akhir' => ['required', 'date_format:Y-m-d', 'gte:tanggal_mulai'],
            'jam_pelajaran' => ['required', 'numeric'],
            'lokasi' => ['required'],
            'penyelenggara' => ['required'],
            'no_sertifikat' => ['required', 'max:100'],
            'tanggal_sertifikat' => ['required', 'date_format:Y-m-d', 'gte:tanggal_akhir'],
            'media_sertifikat' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'file', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_id.required' => 'pegawai harus diisi',
            'pegawai_id.exists' => 'pegawai tidak valid',
            'jenis_diklat_id.required' => 'jenis diklat harus diisi',
            'jenis_diklat_id.exists' => 'jenis diklat tidak valid',
            'tanggal_mulai.required' => 'tanggal mulai harus diisi ',
            'tanggal_mulai.date_format' => 'tanggal harus dalam bentuk format yang valid ',
            'tanggal_akhir.required' => 'tanggal akhir harus diisi ',
            'tanggal_akhir.date_format' => 'tanggal harus dalam bentuk format yang valid ',
            'jam_pelajaran.required' => 'jam pelajaran harus diisi ',
            'jam_pelajaran.numeric' => 'jam pelajaran harus dalam bentuk angka',
            'lokasi.required' => 'lokasi harus diisi',
            'penyelenggara.required' => 'penyelenggara harus diisi',
            'penyelenggara.max' => 'penyelenggara terlalu panjang',
            'no_sertifikat.required' => 'no sertifikat harus diisi',
            'no_sertifikat.max' => 'penyelenggara terlalu panjang',
            'tanggal_sertifikat.required' => 'tanggal sertifikat harus diisi ',
            'tanggal_sertifikat.date_format' => 'tanggal harus dalam bentuk format yang valid ',
            'media_sertifikat.required_without' => 'file sertifikat harus diisi',
            'media_sertifikat.mimes' => 'format file sertifikat harus pdf, jpg, jpeg, png',
            'media_sertifikat.max' => 'ukuran file terlalu besar (maksimal file 1MB)',
        ];
    }
}
