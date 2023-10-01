<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class PendidikanController extends Controller
{
    public function index()
    {
        $result = QueryBuilder::for(Pendidikan::class)
            ->select('id', 'nama')
            ->allowedFilters(['nama'])
            ->when($limit = request('limit'), fn(Builder $builder) => $builder->limit($limit))
            ->get();

        return response()->json($result);
    }
}
