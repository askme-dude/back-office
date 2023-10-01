<?php

namespace App\Services;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiService
{
    public static function getNamaBySearch(int $count = 10): Collection
    {
        return QueryBuilder::for(Pegawai::class)
            ->select('id', 'nama')
            ->allowedFilters('nama')
            ->orderBy('nama')
            ->limit($count)
            ->get();
    }
}
