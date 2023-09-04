<?php

namespace App\Http\Controllers;

use App\Http\Requests\TukinStoreRequest;
use App\Models\Tukin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TukinController extends Controller
{
    public function index()
    {
        return Inertia::render('Tukin/Index',[
            'title' => 'List Tunjangan Kinerja',
            //'list_tukin' => $listTukin,
        ]);
    }

    public function getDataTable(Request $request){
        $paginate = 10;
        if ($request->paginate){
            $paginate = $request->paginate;
        }
        $listTukin = Tukin::query()->when($request->cari, function ($query,$cari){
            $query->orWhere('grade','like',"%{$cari}%");
            $query->orWhere('nominal','like',"%{$cari}%");
            $query->orWhere('keterangan','like',"%{$cari}%");
            })
            ->select('*')
            ->orderBy('grade','asc')
            ->paginate($paginate);
        return response()->json($listTukin);
    }

    public function create()
    {
        return Inertia::render('Tukin/Create',[
            'title'=>'Tunjangan Kinerja',
        ]);
    }

    public function store(TukinStoreRequest $request)
    {
        $data = $request->validated();
        try {
            DB::transaction(function () use ($request, $data){
                //validasi grade
                $cekDataExist = Tukin::where('grade',$request->grade)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'grade' => 'Data grade sudah ada!'
                    ]);
                } else {
                    Tukin::create($data);
                    return redirect()->back()->with('success','Data tukin berhasil disimpan!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-simpan di function store pada TukinController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data tukin gagal disimpan!']);
        }
    }

    public function edit(Tukin $tukin)
    {
        return Inertia::render('Tukin/Edit',[
            'title'=>'Tunjangan Kinerja',
            'dataTukin' => $tukin,
        ]);
    }

    public function update(TukinStoreRequest $request, Tukin $tukin)
    {
        $data = $request->validated();
        try {
            DB::transaction(function () use ($tukin, $data, $request){
                //validasi grade
                $cekDataExist = Tukin::where('grade',$request->grade)
                ->where('id','!=',$tukin->id)
                ->get();
    
                if($cekDataExist->isNotEmpty()){
                    return redirect()->back()->withErrors([
                        'grade' => 'Data grade sudah ada!'
                    ]);
                } else {
                    $tukin->update($data);
                    return redirect()->back()->with('success','Data tukin berhasil di-update!');
                }
            });
        }catch (\Exception $e){
            Log::error($e->getMessage(), ['Data gagal di-update di function store pada TukinController!']);

            return redirect()->back()->withErrors([
            'query' => 'Data tukin gagal di-update!']);
        }
    }

    public function destroy(Tukin $tukin)
    {
        try {
            DB::transaction(function () use ($tukin){
                $tukin->delete();
                return redirect()->back()->with('success','Data tunjangan kinerja berhasil dihapus!');

                Log::info('Data berhasil di-delete di function destroy pada TukinController!');
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['Data gagal di-delete di function destroy pada TukinController!']);
            return redirect()->back()->withErrors([
                'query' => 'Data tunjangan kinerja gagal di-delete!'
            ]);
        }
    }
}
