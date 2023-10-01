<?php

namespace Database\Factories;

use App\Models\JenisDiklat;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatDiklat;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiRiwayatDiklatFactory extends Factory
{
    protected $model = PegawaiRiwayatDiklat::class;

    public function definition(): array
    {
        return [
            'pegawai_id' => Pegawai::factory(),
            'jenis_diklat_id' => JenisDiklat::factory(),
            'nama' => fake()->sentence(),
            'tanggal_mulai' => fake()->date(),
            'tanggal_akhir' => fake()->date(),
            'jam_pelajaran' => fake()->numberBetween(1, 20),
            'lokasi' => fake()->city(),
            'penyelenggara' => fake()->company(),
            'no_sertifikat' => fake()->numerify('###############'),
            'tanggal_sertifikat' => fake()->date(),
        ];
    }
}
