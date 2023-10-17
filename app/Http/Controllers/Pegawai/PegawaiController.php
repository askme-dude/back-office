<?php

namespace App\Http\Controllers\Pegawai;

use App\Enums\GolonganDarah;
use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRequest;
use App\Models\Pegawai;
use App\Models\Agama;
use App\Models\JenisKawin;
use App\Models\JenisPegawai;
use App\Models\StatusPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data =
            QueryBuilder::for(Pegawai::class)
            ->allowedFilters([
                'nip', 'status_dinas',
                AllowedFilter::callback('nama', function (Builder $query, $value) {
                    $query->where('nama_depan', 'like', "%$value%")
                        ->orWhere(
                            'nama_belakang',
                            'like',
                            "%$value%"
                        );
                }),
            ])
            ->allowedSorts([
                'nip', 'status_dinas',
                AllowedSort::callback('nama', function (Builder $query, $value) {
                    $query->orderBy('nama_depan', $value ? 'DESC' : 'ASC');
                })
            ])
            ->paginate(request('per_page', 10))
            ->onEachSide(1)
            ->appends(request()->query());

        return Inertia::render('Pegawai/PegawaiProfil/Index', [
            'pegawai' => fn () => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agama = Agama::all();
        $statusNikah = JenisKawin::all();
        $jenisPegawai = JenisPegawai::all();
        $statusPegawai = StatusPegawai::all();

        return inertia('Pegawai/PegawaiProfil/Create', [
            'agama' => $agama,
            'statusNikah' => $statusNikah,
            'jenisPegawai' => $jenisPegawai,
            'statusPegawai' => $statusPegawai,
            'golonganDarah' => GolonganDarah::cases()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PegawaiRequest $request)
    {
        // dd($request->all());

        $validated = $request->validated();

        Arr::forget($validated, ['media_kartu_pegawai', 'media_foto_pegawai']);

        $pegawai = Pegawai::create($validated);

        if ($request->validated('media_foto_pegawai')) {
            $pegawai->addMediaFromRequest('media_foto_pegawai')->toMediaCollection('media_foto_pegawai');
        }

        if ($request->validated('media_kartu_pegawai')) {
            $pegawai->addMediaFromRequest('media_kartu_pegawai')->toMediaCollection('media_kartu_pegawai');
        }

        return redirect()->route('profil_pegawai.show', $pegawai)->with('toast', [
            'message' => 'Data pegawai berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $profil_pegawai)
    {
        $profil_pegawai->load([
            'agama:id,nama',
            'jenis_kawin:id,nama',
            'jenis_pegawai:id,nama',
            'status_pegawai:id,nama',
        ]);

        return inertia(
            'Pegawai/PegawaiProfil/Show',
            [
                'pegawai' => $profil_pegawai,
                'media_foto_pegawai' => fn () => $profil_pegawai->getFirstMediaUrl('media_foto_pegawai'),
                'media_kartu_pegawai' => fn () => $profil_pegawai->getFirstMediaUrl('media_kartu_pegawai'),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $profil_pegawai)
    {
        $profil_pegawai->load([
            'agama:id,nama',
            'jenis_kawin:id,nama',
            'jenis_pegawai:id,nama',
            'status_pegawai:id,nama',
        ]);

        $agama = Agama::all();
        $statusNikah = JenisKawin::all();
        $jenisPegawai = JenisPegawai::all();
        $statusPegawai = StatusPegawai::all();

        return inertia(
            'Pegawai/PegawaiProfil/Edit',
            [
                'pegawai' => $profil_pegawai,
                'agama' => fn () => $agama,
                'statusNikah' => fn () => $statusNikah,
                'jenisPegawai' => fn () => $jenisPegawai,
                'statusPegawai' => fn () => $statusPegawai,
                'media_foto_pegawai' => fn () => $profil_pegawai->getFirstMediaUrl('media_foto_pegawai'),
                'media_kartu_pegawai' => fn () => $profil_pegawai->getFirstMediaUrl('media_kartu_pegawai'),
                'golonganDarah' => fn () => GolonganDarah::cases()
            ]
        );
    }

    public function update(PegawaiRequest $request, Pegawai $profil_pegawai)
    {
        $validated = $request->validated();

        Arr::forget($validated, ['media_kartu_pegawai', 'media_foto_pegawai']);

        $profil_pegawai->update($validated);

        if ($request->validated('media_foto_pegawai')) {
            $profil_pegawai->clearMediaCollection('media_foto_pegawai');
            $profil_pegawai->addMediaFromRequest('media_foto_pegawai')->toMediaCollection('media_foto_pegawai');
        }

        if ($request->validated('media_kartu_pegawai')) {
            $profil_pegawai->clearMediaCollection('media_kartu_pegawai');
            $profil_pegawai->addMediaFromRequest('media_kartu_pegawai')->toMediaCollection('media_kartu_pegawai');
        }

        return redirect()->route('profil_pegawai.show', $profil_pegawai)->with('toast', [
            'message' => 'Data pegawai berhasil disimpan'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $profil_pegawai)
    {
        // Semua files terkait pegawai akan otomatis terhapus
        $profil_pegawai->delete();

        return redirect()->route('profil_pegawai.index')->with('toast', [
            'message' => 'Data pegawai berhasil dihapus'
        ]);
    }
}
