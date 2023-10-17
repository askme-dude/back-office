<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class PegawaiRiwayatJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->perPage) {
            $perPage = $request->perPage;
        } else {
            $perPage = 10;
        }

        $queryPegawai = Pegawai::query()
            ->when($request->cari, function ($query, $cari) {
                $query->where('nip', 'like', '%' . $cari . '%')
                    ->orWhere('nama_depan', 'like', '%' . $cari . '%')
                    ->orWhere('nama_belakang', 'like', '%' . $cari . '%');
            })->orderBy('id', 'desc');

        return inertia('Pegawai/PegawaiRiwayatJabatan/Index', [
            'pegawai' => $queryPegawai
                ->paginate($perPage)
                ->appends($request->only(['cari', 'perPage'])),
            'filter' => $request->only(['cari', 'perPage']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawai = Pegawai::select('id', 'nama_depan', 'nama_belakang')->get();

        // $unitKerja = UnitKerja::all();

        // $arrayUnitKerja = array();
        // foreach ($unitKerja as $item) {
        //     array_push($arrayUnitKerja, $item->nama);
        // }

        // dd($arrayUnitKerja);

        

        return inertia('Pegawai/PegawaiRiwayatJabatan/Create', [
            'pegawai' => $pegawai,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->pegawai_id['id']);
        // dd($request->pegawai_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
