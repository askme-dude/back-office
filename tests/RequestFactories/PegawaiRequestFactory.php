<?php

namespace Tests\RequestFactories;

use App\Models\Agama;
use App\Models\JenisKawin;
use App\Models\JenisPegawai;
use App\Models\StatusPegawai;
use Illuminate\Http\UploadedFile;
use Worksome\RequestFactories\RequestFactory;

class PegawaiRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('################'),
            'nip' => fake()->unique()->numerify('##################'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'agama_id' => Agama::factory(),
            'jenis_kawin_id' => JenisKawin::factory(),
            'jenis_pegawai_id' => JenisPegawai::factory(),
            'status_pegawai_id' => StatusPegawai::factory(),
            'golongan_darah' => fake()->randomElement(['O', 'A', 'B', 'AB']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('1990-01-01', '2012-12-31')->format('Y-m-d'),
            'email_kantor' => fake()->word() . '@office.com',
            'email_pribadi' => fake()->word() . '@google.com',
            'no_telp' => fake()->numerify('#############'),
            'status_dinas' => fake()->boolean(),
            'no_bpjs' => fake()->numerify('#############'),
            'no_kartu_pegawai' => fake()->numerify('#########'),
            'media_kartu_pegawai' => UploadedFile::fake()->image('kartu_pegawai.jpg'),
            'media_foto_pegawai' => UploadedFile::fake()->image('foto_pegawai.jpg'),
        ];
    }
}
