<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRiwayatPenghargaanRequest;
use App\Jobs\PostSiasnPenghargaanJob;
use App\Jobs\UpdateSiasnRwPenghargaanJob;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Penghargaan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiRiwayatPenghargaanController extends Controller
{
    public function index()
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPenghargaan/Index', [
            'riwayat' => fn() => QueryBuilder::for(PegawaiRiwayatPenghargaan::class)
                ->with(['pegawai:id,nama_depan,nama_belakang', 'penghargaan:id,nama'])
                ->allowedFilters([
                    AllowedFilter::callback('keyword', fn(Builder $query, $value) => $query
                        ->whereHas('pegawai', fn(Builder $builder) => $builder
                            ->where('nama_depan', 'like', "%$value%")
                            ->orWhere('nama_belakang', 'like', "%$value%")))
                ])
                ->orderByDesc('id')
                ->paginate(request('per_page', 15))
                ->appends(request()->query())
        ]);
    }

    public function create()
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPenghargaan/Create', [
            'pegawai' => fn() => Pegawai::query()
                ->select('id', 'nama_depan', 'nama_belakang')
                ->when($nama = request('nama'), fn(Builder $builder) => $builder
                    ->where('nama_depan', 'like', "%$nama%")
                    ->orWhere('nama_belakang', 'like', "%$nama%"))
                ->where('status_dinas', 1)
                ->orderBy('nama_depan')
                ->limit(10)
                ->get(),
            'penghargaan' => fn() => Penghargaan::select('id', 'nama')->get(),
        ]);
    }

    public function store(PegawaiRiwayatPenghargaanRequest $request)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_sk');

        $riwayat = PegawaiRiwayatPenghargaan::create($data);

        if ($request->validated('media_sk')) {
            $riwayat->addMediaFromRequest('media_sk')->toMediaCollection('media_sk');
        }

        Bus::chain([
            new PostSiasnPenghargaanJob($riwayat),
            new UpdateSiasnRwPenghargaanJob($riwayat),
        ])->dispatch();

        return to_route('riwayat-penghargaan.index')
            ->with('toast', ['message' => 'Data berhasil disimpan, sinkronisasi sedang diproses.']);
    }

    public function show(PegawaiRiwayatPenghargaan $riwayat_penghargaan)
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPenghargaan/Show', [
            'riwayat' => $riwayat_penghargaan->load(['pegawai:id,nama_depan,nama_belakang', 'penghargaan:id,nama']),
            'media_sk' => $riwayat_penghargaan->getFirstMediaUrl('media_sk'),
        ]);
    }

    public function edit(PegawaiRiwayatPenghargaan $riwayat_penghargaan)
    {
        return Inertia::render('Pegawai/PegawaiRiwayatPenghargaan/Edit', [
            'riwayat' => $riwayat_penghargaan,
            'currentPegawai' => $riwayat_penghargaan->pegawai()
                ->select('id', 'nama_depan', 'nama_belakang')
                ->first(),
            'pegawai' => fn() => Pegawai::query()
                ->select('id', 'nama_depan', 'nama_belakang')
                ->when($nama = request('nama'), fn(Builder $builder) => $builder
                    ->where('nama_depan', 'like', "%$nama%")
                    ->orWhere('nama_belakang', 'like', "%$nama%"))
                ->limit(10)
                ->get(),
            'penghargaan' => fn() => Penghargaan::select('id', 'nama')->get(),
        ]);
    }

    public function update(PegawaiRiwayatPenghargaanRequest $request, PegawaiRiwayatPenghargaan $riwayat_penghargaan)
    {
        $data = $request->validated();

        Arr::forget($data, 'media_sk');

        $riwayat_penghargaan->update($data);

        Bus::chain([
            new PostSiasnPenghargaanJob($riwayat_penghargaan),
            new UpdateSiasnRwPenghargaanJob($riwayat_penghargaan),
        ])->dispatch();

        if ($request->validated('media_sk')) {
            $riwayat_penghargaan->clearMediaCollection('media_sk');
            $riwayat_penghargaan->addMediaFromRequest('media_sk')->toMediaCollection('media_sk');
        }

        return to_route('riwayat-penghargaan.index')
            ->with('toast', ['message' => 'Data berhasil disimpan, sinkronisasi sedang diproses.']);
    }

    public function destroy(PegawaiRiwayatPenghargaan $riwayat_penghargaan)
    {
        $riwayat_penghargaan->delete();

        return to_route('riwayat-penghargaan.index')
            ->with('toast', ['message' => 'Data berhasil dihapus, silakan hapus data terkait pada SIASN']);
    }
}
