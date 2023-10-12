<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\JenisUnitKerja;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('UnitKerja/Index',[
            'title' => 'Unit Kerja',
            //'list_umak' => $listUmak,
        ]);
    }

    public function getDataTable(Request $request){
        $paginate = 10;
        if ($request->paginate){
            $paginate = $request->paginate;
        }

        $listUnit = UnitKerja::query()->when($request->cari, function ($query,$cari){
        $query->orWhere('jenis_unit_kerja.nama','like',"%{$cari}%");
        $query->orWhere('unit_kerja.nama','like',"%{$cari}%");
        $query->orWhere('unit_kerja.singkatan','like',"%{$cari}%");
        $query->orWhere('unit_kerja.keterangan','like',"%{$cari}%");
        })

        ->join('jenis_unit_kerja','jenis_unit_kerja.id','=','unit_kerja.jenis_unit_kerja_id')
        ->select('unit_kerja.*',
            'jenis_unit_kerja.nama as nama_jenis_unit_kerja',

        )
        ->orderBy('jenis_unit_kerja.id','asc')
        ->orderBy('unit_kerja.nama','asc')
        ->paginate($paginate);
        return response()->json($listUnit);
    }

    public function create()
    {
        $jenisUnit = JenisUnitKerja::select('id','nama')->get();
       
        return Inertia::render('UnitKerja/Create',[
            'title'=>'Unit Kerja',
            'jenisUnit'=>$jenisUnit,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis_unit_kerja_id' => ['required', 'integer', 'min:1', 'max:999'],
            'singkatan' => ['max:18'],
            'keterangan' => ['max:100'],
        ],
        [
            'nama.required'=>'data nama unit kerja harus diisi!',
            'jenis_unit_kerja_id.required'=>'data jenis unit kerja harus diisi!',
        ]);

        try {
            DB::transaction(function () use ($request, $data){
                //validasi golongan
                $cekDataExist = UnitKerja::where('jenis_unit_kerja_id',$request->jenis_unit_kerja_id)
                ->where('nama',$request->nama)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'nama' => 'Data nama unit kerja dan jenis unit kerja sudah ada!',
                    ]);
                } else {
                    UnitKerja::create($data);
                    return redirect()->back()->with('success','Data unit kerja berhasil disimpan!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-simpan di function store pada UnitKerjaController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data unit kerja gagal disimpan!']);
        }
    }

    public function edit(UnitKerja $unit_kerja)
    {
        $jenisUnit = JenisUnitKerja::select('id','nama')->get();
        
        return Inertia::render('UnitKerja/Edit',[
            'title'=>'Unit Kerja',
            'dataUnit' => $unit_kerja,
            'jenisUnit'=>$jenisUnit,
        ]);
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis_unit_kerja_id' => ['required', 'integer', 'min:1', 'max:999'],
            'singkatan' => ['max:18'],
            'keterangan' => ['max:100'],
        ],
        [
            'nama.required'=>'data nama unit kerja harus diisi!',
            'jenis_unit_kerja_id.required'=>'data jenis unit kerja harus diisi!',
        ]);
        
        try {
            DB::transaction(function () use ($unit_kerja, $data, $request){
                //validasi golongan
                $cekDataExist = UnitKerja::where('jenis_unit_kerja_id',$request->jenis_unit_kerja_id)
                ->where('nama',$request->nama)
                ->where('id','!=',$unit_kerja->id)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'nama' => 'Data nama unit kerja dan jenis unit kerja sudah ada!',
                    ]);
                } else {
                    $unit_kerja->update($data);
                    return redirect()->back()->with('success','Data unit kerja berhasil di-update!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-update di function update pada UnitKerjaController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data unit kerja gagal di-update!']);
        }
    }

    // public function update(Request $request, UangMakan $umak)
    // {
    //     $data = $request->validate([
    //         'golongan_id' => ['required', 'numeric'],
    //         'nominal' => ['required', 'numeric']
    //     ]);

    //     $umak->update($data);

    //     return back()->with('toast', ['message' => 'Data berhasil disimpan']);
    // }

    public function destroy(UnitKerja $unit_kerja)
    {
        try {
            DB::transaction(function () use ($unit_kerja){
                $unit_kerja->delete();
                return redirect()->back()->with('success','Data unit kerja berhasil dihapus!');

                Log::info('Data berhasil di-delete di function destroy pada UnitKerjaController!');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['Data gagal di-delete di function destroy pada UnitKerjaController!']);
            return redirect()->back()->withErrors([
                'query' => 'Data unit kerja gagal di-delete!'
            ]);
        }
    }
}
