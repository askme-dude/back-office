<?php

namespace App\Http\Controllers\Pegawai;

use App\Filters\FilterPegawaiAlamat;
use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiAlamatRequest;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\PegawaiAlamat;
use App\Models\Propinsi;
use App\Services\PegawaiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PegawaiAlamatController extends Controller
{
    public function index()
    {
        return Inertia::render('Pegawai/PegawaiAlamat/Index', [
            'pegawaiAlamat' => fn() => QueryBuilder::for(PegawaiAlamat::class)
                ->allowedFilters([
                    AllowedFilter::custom('keyword', new FilterPegawaiAlamat)
                ])
                ->orderByDesc('id')
                ->with(['pegawai:id,nama', 'propinsi', 'kota', 'kecamatan', 'desa'])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query()),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'propinsi_id' => 'numeric',
            'kota_id' => 'numeric',
            'kecamatan_id' => 'numeric',
        ]);

        return Inertia::render('Pegawai/PegawaiAlamat/Create', [
            'title' => 'Tambah Alamat',
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'propinsi' => fn() => Propinsi::all(),
            'kota' => Inertia::lazy(fn() => Kota::where('propinsi_id', $request->get('propinsi_id'))->get()),
            'kecamatan' => Inertia::lazy(fn() => Kecamatan::where('kota_id', $request->get('kota_id'))->get()),
            'desa' => Inertia::lazy(fn() => Desa::where('kecamatan_id', $request->get('kecamatan_id'))->get()),
        ]);
    }

    public function store(PegawaiAlamatRequest $request)
    {
        $pegawaiAlamat = PegawaiAlamat::where('pegawai_id', $request->pegawai_id)->get();

        if (count($pegawaiAlamat) >= 2) {
            return back()->withErrors([
                'pegawai_id' => 'Pegawai sudah memiliki dua alamat'
            ]);
        }

        foreach ($pegawaiAlamat as $alamat) {
            if ($alamat->tipe_alamat === $request->tipe_alamat) {
                return back()->withErrors([
                    'tipe_alamat' => 'Pegawai sudah memiliki alamat ' . $alamat->tipe_alamat
                ]);
            }
        }

        PegawaiAlamat::create($request->validated());

        return to_route('alamat.index')->with('toast', [
            'message' => 'Data alamat berhasil disimpan',
        ]);
    }

    public function edit(Request $request, PegawaiAlamat $alamat)
    {
        $request->validate([
            'propinsi_id' => 'numeric',
            'kota_id' => 'numeric',
            'kecamatan_id' => 'numeric',
        ]);

        return Inertia::render('Pegawai/PegawaiAlamat/Edit', [
            'pegawaiAlamat' => $alamat,
            'pegawai' => fn() => PegawaiService::getNamaBySearch(),
            'currentPegawai' => $alamat->pegawai()->select(['id', 'nama'])->first(),
            'propinsi' => fn() => Propinsi::all(),
            'kota' => fn() => Kota::when($propinsi_id = $request->get('propinsi_id'), fn(Builder $builder) => $builder
                ->where('propinsi_id', $propinsi_id), fn(Builder $builder) => $builder
                ->where('propinsi_id', $alamat->propinsi_id))->get(),
            'kecamatan' => fn() => Kecamatan::when($kota_id = $request->get('kota_id'), fn(Builder $builder) => $builder
                ->where('kota_id', $kota_id), fn(Builder $builder) => $builder
                ->where('kota_id', $alamat->kota_id))->get(),
            'desa' => fn() => Desa::when($kecamatan_id = $request->get('kecamatan_id'), fn(Builder $builder) => $builder
                ->where('kecamatan_id', $kecamatan_id), fn(Builder $builder) => $builder
                ->where('kecamatan_id', $alamat->kecamatan_id))->get()
        ]);
    }

    public function update(PegawaiAlamatRequest $request, PegawaiAlamat $alamat)
    {
        $alamat->update($request->validated());

        return to_route('alamat.index')->with('toast', [
            'message' => 'Data alamat berhasil diubah'
        ]);
    }

    public function destroy(PegawaiAlamat $alamat)
    {
        $alamat->delete();

        return to_route('alamat.index')->with('toast', [
            'message' => 'Data alamat berhasil dihapus'
        ]);
    }
}
