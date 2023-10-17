<?php

namespace App\Http\Controllers\Siasn;

use App\Http\Controllers\Controller;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SiasnPenghargaanController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Siasn/Penghargaan/Index', [
            'penghargaan' => QueryBuilder::for(SiasnPnsRwPenghargaan::class)
                ->with(['siasnPegawai:id,nama', 'penghargaan:id,nama'])
                ->allowedFilters([
                    AllowedFilter::callback('pegawai', fn(Builder $query, $value) => $query
                        ->whereRelation('siasnPegawai', 'nama', 'like', "%$value%"))
                ])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query())
        ]);
    }
}
