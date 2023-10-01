<?php

namespace App\Services;

use App\Models\UnitKerja;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

class UnitKerjaService
{
    public static function getNamaBySearch(int $count = 10): Collection
    {
        return QueryBuilder::for(UnitKerja::class)
            ->select('id', 'nama')
            ->allowedFilters('nama')
            ->limit($count)
            ->get();
    }
}
