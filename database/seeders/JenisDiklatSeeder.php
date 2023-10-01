<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisDiklatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_diklat = array(
            array(
                "bkn_id" => "2",
                "nama" => "Diklat Fungsional",
                "jenis_kursus_sertifikat" => "F"),
            array(
                "bkn_id" => "3",
                "nama" => "Diklat Teknis",
                "jenis_kursus_sertifikat" => "T"),
            array(
                "bkn_id" => "5",
                "nama" => "Pelatihan Manajerial",
                "jenis_kursus_sertifikat" => "-"),
            array(
                "bkn_id" => "6",
                "nama" => "Pelatihan Sosial Kultural",
                "jenis_kursus_sertifikat" => "-"),
            array(
                "bkn_id" => "7",
                "nama" => "Sosialisasi",
                "jenis_kursus_sertifikat" => "P"),
            array(
                "bkn_id" => "8",
                "nama" => "Bimbingan Teknis",
                "jenis_kursus_sertifikat" => "P"),
            array(
                "bkn_id" => "9",
                "nama" => "Seminar",
                "jenis_kursus_sertifikat" => "P"),
            array(
                "bkn_id" => "10",
                "nama" => "Magang",
                "jenis_kursus_sertifikat" => "P"),
            array(
                "bkn_id" => "1",
                "nama" => "Diklat Struktural",
                "jenis_kursus_sertifikat" => "-"),
            array(
                "bkn_id" => "4",
                "nama" => "Workshop",
                "jenis_kursus_sertifikat" => "P")
        );
        foreach ($jenis_diklat as $data) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            DB::table('jenis_diklat')->insert($data);
        }
    }
}
