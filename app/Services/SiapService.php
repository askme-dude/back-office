<?php

namespace App\Services;

use App\Integration\Siap\Model\Pegawai;

class SiapService
{
    public static function getAllNip(): \Illuminate\Support\Collection
    {
        return Pegawai::select('NIPBaru as nip')
            ->whereNotNull('NIPBaru')
            ->get()
            ->pluck('nip');
    }
}
