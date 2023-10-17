<?php

namespace App\Services;

use App\Integration\Presensi\Model\Pegawai;

class PresensiService
{
    public static function getAllNip(): \Illuminate\Support\Collection
    {
        return Pegawai::select('nipp as nip')
            ->whereNotNull('nipp')
            ->get()
            ->pluck('nip');
    }

    public static function getAllNipAndEnroll()
    {
        return Pegawai::select('nipp as nip', 'enroll_no')
            ->whereNotNull('nipp')
            ->get();
    }

    public static function getEnrollNumberByNip(int|string $nip): null|string
    {
        return Pegawai::select('enroll_no')
            ->firstWhere('nipp', '=', $nip)
            ?->enroll_no;
    }
}
