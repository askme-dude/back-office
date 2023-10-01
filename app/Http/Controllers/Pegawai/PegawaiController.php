<?php

namespace App\Http\Controllers\Pegawai;

use App\Enums\GolonganDarah;
use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRequest;
use App\Models\Agama;
use App\Models\JenisKawin;
use App\Models\JenisPegawai;
use App\Models\Pegawai;
use App\Models\StatusPegawai;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiController extends Controller
{
    public function index()
    {
        $data = QueryBuilder::for(Pegawai::class)
            ->allowedFilters(['nama', 'nip', 'status_dinas'])
            ->allowedSorts(['nama', 'nip', 'status_dinas'])
            ->paginate(request('per_page', 15))
            ->onEachSide(1)
            ->appends(request()->query());

        return inertia('Pegawai/Index', [
            'pegawai' => fn() => $data
        ]);
    }

    public function create()
    {
        $agama = Agama::all();
        $statusNikah = JenisKawin::all();
        $jenisPegawai = JenisPegawai::all();
        $statusPegawai = StatusPegawai::all();

        return inertia('Pegawai/Create', [
            'agama' => $agama,
            'statusNikah' => $statusNikah,
            'jenisPegawai' => $jenisPegawai,
            'statusPegawai' => $statusPegawai,
            'golonganDarah' => GolonganDarah::cases()
        ]);
    }

    public function store(PegawaiRequest $request)
    {
        $validated = $request->validated();

        Arr::forget($validated, ['media_kartu_pegawai', 'media_foto_pegawai']);

        $pegawai = Pegawai::create($validated);

        if ($request->validated('media_foto_pegawai')) {
            $pegawai->addMediaFromRequest('media_foto_pegawai')->toMediaCollection('media_foto_pegawai');
        }

        if ($request->validated('media_kartu_pegawai')) {
            $pegawai->addMediaFromRequest('media_kartu_pegawai')->toMediaCollection('media_kartu_pegawai');
        }

        return redirect()->route('pegawai.show', $pegawai)->with('toast', [
            'message' => 'Data pegawai berhasil disimpan'
        ]);
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load([
            'agama:id,nama',
            'jenis_kawin:id,nama',
            'jenis_pegawai:id,nama',
            'status_pegawai:id,nama',
        ]);

        return inertia('Pegawai/Show', [
                'pegawai' => $pegawai,
                'media_foto_pegawai' => fn() => $pegawai->getFirstMediaUrl('media_foto_pegawai'),
                'media_kartu_pegawai' => fn() => $pegawai->getFirstMediaUrl('media_kartu_pegawai'),
            ]
        );
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load([
            'agama:id,nama',
            'jenis_kawin:id,nama',
            'jenis_pegawai:id,nama',
            'status_pegawai:id,nama',
        ]);

        $agama = Agama::all();
        $statusNikah = JenisKawin::all();
        $jenisPegawai = JenisPegawai::all();
        $statusPegawai = StatusPegawai::all();

        return inertia('Pegawai/Edit', [
                'pegawai' => $pegawai,
                'agama' => fn() => $agama,
                'statusNikah' => fn() => $statusNikah,
                'jenisPegawai' => fn() => $jenisPegawai,
                'statusPegawai' => fn() => $statusPegawai,
                'media_foto_pegawai' => fn() => $pegawai->getFirstMediaUrl('media_foto_pegawai'),
                'media_kartu_pegawai' => fn() => $pegawai->getFirstMediaUrl('media_kartu_pegawai'),
                'golonganDarah' => fn() => GolonganDarah::cases()
            ]
        );
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        $validated = $request->validated();

        Arr::forget($validated, ['media_kartu_pegawai', 'media_foto_pegawai']);

        $pegawai->update($validated);

        if ($request->validated('media_foto_pegawai')) {
            $pegawai->clearMediaCollection('media_foto_pegawai');
            $pegawai->addMediaFromRequest('media_foto_pegawai')->toMediaCollection('media_foto_pegawai');
        }

        if ($request->validated('media_kartu_pegawai')) {
            $pegawai->clearMediaCollection('media_kartu_pegawai');
            $pegawai->addMediaFromRequest('media_kartu_pegawai')->toMediaCollection('media_kartu_pegawai');
        }

        return redirect()->route('pegawai.show', $pegawai)->with('toast', [
            'message' => 'Data pegawai berhasil disimpan'
        ]);
    }

    public function destroy(Pegawai $pegawai)
    {
        // Semua files terkait pegawai akan otomatis terhapus
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('toast', [
            'message' => 'Data pegawai berhasil dihapus'
        ]);
    }
}
