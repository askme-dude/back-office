<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\StatusPegawai;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PegawaiStatusController extends Controller
{
    public function index()
    {
        return Inertia::render('PegawaiStatus/Index',[
            'title' => 'List Pegawai Status',
            //'list_tukin' => $listTukin,
        ]);
    }

    public function getDataTable(Request $request){
        $paginate = 10;
        if ($request->paginate){
            $paginate = $request->paginate;
        }
        $listStatus = StatusPegawai::query()->when($request->cari, function ($query,$cari){
            $query->orWhere('nama','like',"%{$cari}%");
            })
            ->select('*')
            ->orderBy('id','asc')
            ->paginate($paginate);
        return response()->json($listStatus);
    }

    public function create()
    {
        return Inertia::render('PegawaiStatus/Create',[
            'title'=>'Pegawai Status',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:30'],
        ],
        [
            'nama.required'=>'nama status harus diisi!',
        ]);

        try {
            DB::transaction(function () use ($request, $data){
                //validasi grade
                $cekDataExist = StatusPegawai::where('nama',$request->nama)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'nama' => 'Data status pegawai sudah ada!'
                    ]);
                } else {
                    StatusPegawai::create($data);
                    return redirect()->back()->with('success','Data status pegawai berhasil disimpan!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-simpan di function store pada PegawaiStatusController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data status pegawai gagal disimpan!']);
        }
    }

    public function edit(StatusPegawai $pegawai_status)
    {
        return Inertia::render('PegawaiStatus/Edit',[
            'title'=>'Pegawai Status',
            'dataStatus' => $pegawai_status,
        ]);
    }

    public function update(Request $request, StatusPegawai $pegawai_status)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:30'],
        ],
        [
            'nama.required'=>'nama status harus diisi!',
        ]);

        try {
            DB::transaction(function () use ($pegawai_status, $data, $request){
                //validasi grade
                $cekDataExist = StatusPegawai::where('nama',$request->nama)
                ->where('id','!=',$pegawai_status->id)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'nama' => 'Data status pegawai sudah ada!'
                    ]);
                } else {
                    $pegawai_status->update($data);
                    return redirect()->back()->with('success','Data status pegawai berhasil di-update!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-update di function update pada PegawaiStatusController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data status pegawai gagal di-update!']);
        }
    }

    public function destroy(StatusPegawai $pegawai_status)
    {
        try {
            DB::transaction(function () use ($pegawai_status){
                $pegawai_status->delete();
                return redirect()->back()->with('success','Data status pegawai berhasil dihapus!');

                Log::info('Data berhasil di-delete di function destroy pada PegawaiStatusController!');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['Data gagal di-delete di function destroy pada PegawaiStatusController!']);
            return redirect()->back()->withErrors([
                'query' => 'Data status pegawai gagal di-delete!'
            ]);
        }
    }
}
