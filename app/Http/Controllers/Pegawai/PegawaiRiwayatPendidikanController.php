<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRiwayatPendidikanRequest;
use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPendidikan;
use App\Models\Pendidikan;
use App\Models\Propinsi;
use App\Services\PegawaiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiRiwayatPendidikanController extends Controller
{
    public function index()
    {
        $riwayat = PegawaiRiwayatPendidikan::with(['pegawai:id,nama', 'pendidikan:id,nama', 'propinsi:id,nama', 'kota:id,nama'])
            ->find(request('riwayat_id'));

        return Inertia::render('Pegawai/PegawaiRiwayatPendidikan/Index', [
            'title' => 'Riwayat Pendidikan Pegawai',
            'mediaIjazahUrl' => Inertia::lazy(fn() => $riwayat?->getFirstMediaUrl('media_sertifikat')),
            'selectedRiwayat' => Inertia::lazy(fn() => $riwayat),
            'riwayatPendidikan' => fn() => QueryBuilder::for(PegawaiRiwayatPendidikan::class)
                ->with(['pegawai:id,nama', 'pendidikan:id,nama'])
                ->allowedFilters(['nama_instansi', 'no_ijazah', 'tanggal_ijazah',
                    // Filter by relationship
                    AllowedFilter::callback('pegawai', fn(Builder $builder, $value) => $builder
                        ->whereRelation('pegawai', 'nama', 'like', "%$value%")),
                    AllowedFilter::callback('pendidikan', fn(Builder $builder, $value) => $builder
                        ->whereRelation('pendidikan', 'nama', 'like', "%$value%")),
                ])
                ->allowedSorts(['nama_instansi', 'no_ijazah', 'tanggal_ijazah',
                    // Sort by relationship
                    AllowedSort::callback('pegawai', fn(Builder $builder, $val) => $builder->orderBy(Pegawai::select('nama')
                        ->whereColumn('pegawai.id', '=', 'pegawai_riwayat_pendidikan.pegawai_id'), $val ? 'DESC' : 'ASC')),
                    AllowedSort::callback('pendidikan', fn(Builder $builder, $val) => $builder->orderBy(Pendidikan::select('nama')
                        ->whereColumn('pendidikan.id', '=', 'pegawai_riwayat_pendidikan.pendidikan_id'), $val ? 'DESC' : 'ASC'))
                ])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query())
        ]);
    }

    public function create()
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPendidikan/Create', [
            'title' => 'Rekam Riwayat Pendidikan',
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'pendidikan' => fn() => Pendidikan::limit(10)->get(),
            'propinsi' => fn() => Propinsi::select('id', 'nama')->get(),
            'kota' => Inertia::lazy(fn() => Kota::where('propinsi_id', request('propinsi_id'))->get()),
        ]);
    }

    public function store(PegawaiRiwayatPendidikanRequest $request)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_ijazah');

        $riwayatPendidikan = PegawaiRiwayatPendidikan::create($data);

        if ($request->hasFile('media_ijazah')) {
            $riwayatPendidikan->addMediaFromRequest('media_ijazah')->toMediaCollection('media_ijazah');
        }

        return to_route('riwayat-pendidikan.index')->with('toast', ['message' => 'Riwayat pendidikan berhasil disimpan']);
    }

    public function edit(PegawaiRiwayatPendidikan $riwayat_pendidikan)
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPendidikan/Edit', [
            'title' => 'Edit Riwayat Pendidikan',
            'riwayatPendidikan' => fn() => $riwayat_pendidikan,
            'currentPegawai' => fn() => $riwayat_pendidikan->pegawai()->select('id', 'nama')->first(),
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'pendidikan' => fn() => Pendidikan::limit(10)->get(),
            'propinsi' => fn() => Propinsi::select('id', 'nama')->get(),
            'kota' => fn() => Kota::when($propinsi_id = request('propinsi_id'), fn(Builder $builder) => $builder
                ->where('propinsi_id', $propinsi_id), fn(Builder $builder) => $builder
                ->where('propinsi_id', $riwayat_pendidikan->propinsi_id))->get(),
        ]);
    }

    public function update(PegawaiRiwayatPendidikanRequest $request, PegawaiRiwayatPendidikan $riwayat_pendidikan)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_ijazah');

        $riwayat_pendidikan->update($data);

        if ($request->hasFile('media_ijazah')) {
            $riwayat_pendidikan->clearMediaCollection('media_ijazah');
            $riwayat_pendidikan->addMediaFromRequest('media_ijazah')->toMediaCollection('media_ijazah');
        }

        return to_route('riwayat-pendidikan.index')->with('toast', [
            'message' => 'Riwayat pendidikan berhasil diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PegawaiRiwayatPendidikan $riwayat_pendidikan)
    {
        // File terkait riwayat pendidikan otomatis terhapus
        $riwayat_pendidikan->delete();

        return to_route('riwayat-pendidikan.index')
            ->with('toast', ['message' => 'Riwayat pendidikan berhasil dihapus']);
    }
}
