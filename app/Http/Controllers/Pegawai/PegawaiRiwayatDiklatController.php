<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRiwayatDiklatRequest;
use App\Models\JenisDiklat;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatDiklat;
use App\Services\PegawaiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiRiwayatDiklatController extends Controller
{
    public function index()
    {
        $diklat = PegawaiRiwayatDiklat::find(request('diklat_id'));

        return Inertia::render('Pegawai/PegawaiRiwayatDiklat/Index', [
            'title' => 'Riwayat Diklat Pegawai',
            'media_sertifikat_url' => Inertia::lazy(fn() => $diklat?->getFirstMediaUrl('media_sertifikat')),
            'riwayatDiklat' => fn() => QueryBuilder::for(PegawaiRiwayatDiklat::class)
                ->with(['pegawai:id,nama', 'jenisDiklat:id,nama'])
                ->allowedFilters(['tanggal_mulai', 'nama', 'penyelenggara',
                    // Filter by relationship
                    AllowedFilter::callback('pegawai', fn(Builder $builder, $value) => $builder
                        ->whereRelation('pegawai', 'nama', 'like', "%$value%")),
                    AllowedFilter::callback('jenis_diklat', fn(Builder $builder, $value) => $builder
                        ->whereRelation('jenisDiklat', 'nama', 'like', "%$value%"))
                ])
                ->allowedSorts(['tanggal_mulai', 'nama', 'penyelenggara',
                    // Sort by relationship
                    AllowedSort::callback('pegawai', fn(Builder $builder, $val) => $builder->orderBy(Pegawai::select('nama')
                        ->whereColumn('pegawai.id', '=', 'pegawai_riwayat_diklat.pegawai_id'), $val ? 'DESC' : 'ASC')),
                    AllowedSort::callback('jenis_diklat', fn(Builder $builder, $val) => $builder->orderBy(JenisDiklat::select('nama')
                        ->whereColumn('jenis_diklat.id', '=', 'pegawai_riwayat_diklat.jenis_diklat_id'), $val ? 'DESC' : 'ASC'))
                ])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query())
        ]);
    }

    public function create()
    {
        return Inertia::render('Pegawai/PegawaiRiwayatDiklat/Create', [
            'title' => 'Tambah Riwayat Diklat',
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'jenisDiklat' => fn() => JenisDiklat::select('id', 'nama')->get()
        ]);
    }

    public function store(PegawaiRiwayatDiklatRequest $request)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_sertifikat');

        $diklat = PegawaiRiwayatDiklat::create($data);

        if ($request->hasFile('media_sertifikat')) {
            $diklat->addMediaFromRequest('media_sertifikat')->toMediaCollection('media_sertifikat');
        }

        return to_route('riwayat-diklat.index')->with('toast', [
            'message' => 'Riwayat diklat berhasil disimpan'
        ]);
    }

    public function edit(PegawaiRiwayatDiklat $riwayat_diklat)
    {
        return Inertia::render('Pegawai/PegawaiRiwayatDiklat/Edit', [
            'title' => 'Edit Riwayat Diklat',
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'jenisDiklat' => fn() => JenisDiklat::select('id', 'nama')->get(),
            'riwayatDiklat' => fn() => $riwayat_diklat,
            'currentPegawai' => fn() => $riwayat_diklat->pegawai()->select('id', 'nama')->first(),
        ]);
    }

    public function update(PegawaiRiwayatDiklatRequest $request, PegawaiRiwayatDiklat $riwayat_diklat)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_sertifikat');

        $riwayat_diklat->update($data);

        if ($request->hasFile('media_sertifikat')) {
            $riwayat_diklat->clearMediaCollection('media_sertifikat');
            $riwayat_diklat->addMediaFromRequest('media_sertifikat')->toMediaCollection('media_sertifikat');
        }

        return to_route('riwayat-diklat.index')->with('toast', [
            'message' => 'Riwayat diklat berhasil diubah'
        ]);
    }

    public function destroy(PegawaiRiwayatDiklat $riwayat_diklat)
    {
        // File terkait diklat otomatis terhapus
        $riwayat_diklat->delete();

        return to_route('riwayat-diklat.index')->with('toast', [
            'message' => 'Riwayat diklat berhasil dihapus'
        ]);
    }
}
