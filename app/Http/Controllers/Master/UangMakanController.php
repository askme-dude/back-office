<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\UangMakan;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UangMakanController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Umak/Index',[
            'title' => 'Uang Makan',
            //'list_umak' => $listUmak,
        ]);
    }

    public function getDataTable(Request $request){
        $paginate = 10;
        if ($request->paginate){
            $paginate = $request->paginate;
        }

        $listUmak = UangMakan::query()->when($request->cari, function ($query,$cari){
        $query->orWhere('golongan.nama','like',"%{$cari}%");
        $query->orWhere('uang_makan.nominal','like',"%{$cari}%");
        })

        ->join('golongan','golongan.id','=','uang_makan.golongan_id')
        ->select('uang_makan.*',
            'golongan.nama as nama_golongan',

        )
        ->orderBy('golongan.nama','asc')
        ->paginate($paginate);
        return response()->json($listUmak);
    }

    public function create()
    {
        $golongan = Golongan::select('id','nama')->get();
       
        return Inertia::render('Umak/Create',[
            'title'=>'Uang Makan',
            'golongan'=>$golongan,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'golongan_id' => ['required', 'integer', 'min:1', 'max:999'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999999999']
        ],
        [
            'golongan_id.required'=>'data golongan harus diisi!',
            'golongan_id.integer'=>'data golongan harus bilangan bulat positif!',
            'nominal.required'=>'data nominal harus diisi!',
            'nominal.integer'=>'data nominal harus bilangan bulat positif!',
        ]);

        try {
            DB::transaction(function () use ($request, $data){
                //validasi golongan
                $cekDataExist = UangMakan::where('golongan_id',$request->golongan_id)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'golongan_id' => 'Data golongan sudah ada!'
                    ]);
                } else {
                    UangMakan::create($data);
                    return redirect()->back()->with('success','Data uang makan berhasil disimpan!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-simpan di function store pada UangMakanController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data uang makan gagal disimpan!']);
        }
    }

    public function edit(UangMakan $umak)
    {
        $golongan = Golongan::select('id','nama')->get();
        
        return Inertia::render('Umak/Edit',[
            'title'=>'Uang Makan',
            'dataUmak' => $umak,
            'golongan'=>$golongan,
        ]);
    }

    public function update(Request $request, UangMakan $umak)
    {
        $data = $request->validate([
            'golongan_id' => ['required', 'integer', 'min:1', 'max:999'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999999999']
        ],
        [
            'golongan_id.required'=>'data golongan harus diisi!',
            'golongan_id.integer'=>'data golongan harus bilangan bulat positif!',
            'nominal.required'=>'data nominal harus diisi!',
            'nominal.integer'=>'data nominal harus bilangan bulat positif!',
        ]);
        
        try {
            DB::transaction(function () use ($umak, $data, $request){
                //validasi golongan
                $cekDataExist = UangMakan::where('golongan_id',$request->golongan_id)
                ->where('id','!=',$umak->id)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'golongan_id' => 'Data golongan sudah ada!'
                    ]);
                } else {
                    $umak->update($data);
                    return redirect()->back()->with('success','Data uang makan berhasil di-update!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-update di function update pada UangMakanController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data uang makan gagal di-update!']);
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

    public function destroy(UangMakan $umak)
    {
        try {
            DB::transaction(function () use ($umak){
                $umak->delete();
                return redirect()->back()->with('success','Data uang makan berhasil dihapus!');

                Log::info('Data berhasil di-delete di function destroy pada UangMakanController!');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['Data gagal di-delete di function destroy pada UangMakanController!']);
            return redirect()->back()->withErrors([
                'query' => 'Data uang makan gagal di-delete!'
            ]);
        }
    }
}
