<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterPegawaiAlamat implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $likeValue = '%' . $value . '%';

        $query->whereRelation('pegawai', 'nama', 'like', $likeValue)
            ->orWhereRelation('propinsi', 'nama', 'like', $likeValue)
            ->orWhereRelation('kota', 'nama', 'like', $likeValue)
            ->orWhereRelation('kecamatan', 'nama', 'like', $likeValue)
            ->orWhereRelation('desa', 'nama', 'like', $likeValue)
            ->orWhere('alamat', 'like', $likeValue);
    }
}
