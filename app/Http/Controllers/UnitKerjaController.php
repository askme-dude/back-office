<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitKerjaRequest;
use App\Models\JenisUnitKerja;
use App\Models\UnitKerja;
use App\Services\UnitKerjaService;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class UnitKerjaController extends Controller
{
    public function index()
    {
        return Inertia::render('UnitKerja/Index', [
            'unitKerja' => fn() => QueryBuilder::for(UnitKerja::class)
                ->with('parent')
                ->allowedFilters('nama')
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query()),
            'selectedUnit' => fn() => UnitKerja::with(['parent', 'jenisUnitKerja'])
                ->find(request('unit_kerja_id'))
        ]);
    }

    public function create()
    {
        return Inertia::render('UnitKerja/Create', [
            'unitOptions' => fn() => UnitKerjaService::getNamaBySearch(),
            'jenisUnitKerja' => fn() => JenisUnitKerja::all(),
        ]);
    }

    public function store(UnitKerjaRequest $request)
    {
        UnitKerja::create($request->validated());

        return to_route('unit-kerja.index')->with('toast', [
            'message' => 'Data unit kerja berhasil disimpan'
        ]);
    }

    public function edit(UnitKerja $unit_kerja)
    {
        return Inertia::render('UnitKerja/Edit', [
            'unitOptions' => fn() => UnitKerjaService::getNamaBySearch(),
            'jenisUnitKerja' => fn() => JenisUnitKerja::all(),
            'currentUnit' => $unit_kerja->load('parent'),
        ]);
    }

    public function update(UnitKerjaRequest $request, UnitKerja $unit_kerja)
    {
        $unit_kerja->update($request->validated());

        return to_route('unit-kerja.index')->with('toast', [
            'message' => 'Data unit kerja berhasil diubah'
        ]);
    }

    public function destroy(UnitKerja $unit_kerja)
    {
        $unit_kerja->delete();

        return to_route('unit-kerja.index')->with('toast', [
            'message' => 'Data unit kerja berhasil dihapus'
        ]);
    }
}
