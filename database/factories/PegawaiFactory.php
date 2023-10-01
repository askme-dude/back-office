<?php

namespace Database\Factories;

use App\Models\Agama;
use App\Models\JenisKawin;
use App\Models\JenisPegawai;
use App\Models\StatusPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
            'email_kantor' => fake()->email(),
            'email_pribadi' => fake()->email(),
            'no_telp' => fake()->numerify('#############'),
            'status_dinas' => fake()->boolean(),
            'no_bpjs' => fake()->numerify('#############'),
            'no_kartu_pegawai' => fake()->numerify('#########'),
        ];
    }
}
