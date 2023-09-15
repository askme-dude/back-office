<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\PegawaiSaldoCuti;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PegawaiSaldoCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Pegawai/PegawaiSaldoCuti/Index',[
            'title'=>'Saldo PegawaiCuti Pegawai'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Pegawai/PegawaiSaldoCuti/Create',[
            'title'=>'Tambah Saldo Pegawai',
            'pegawai'=>Pegawai::getAllDataPegawai()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'=>['required','exists:pegawai,id'],
            'saldo_n'=>['required','numeric','digits:2'],
            'saldo_n_1'=>['required','numeric','digits:1'],
            'saldo_n_2'=>['required','numeric','digits:1'],

        ],[
            'pegawai_id.required'=>'pegawai harus diisi',
            'pegawai_id.exists'=>'pegawai tidak valid',
            'saldo_n.required'=>'saldo harus diisi',
            'saldo_n.numeric'=>'saldo harus berupa angka',
            'saldo_n.digits'=>'saldo maksimal 2 digit',
            'saldo_n_1.required'=>'saldo harus diisi',
            'saldo_n_1.numeric'=>'saldo harus berupa angka',
            'saldo_n_1.digits'=>'saldo maksimal 1 digit',
            'saldo_n_2.required'=>'saldo harus diisi',
            'saldo_n_2.numeric'=>'saldo harus berupa angka',
            'saldo_n_2.digits'=>'saldo maksimal 1 digit'
        ]);
        $cekCuti = PegawaiSaldoCuti::where('pegawai_id',$request->pegawai_id)->first();
        if ($cekCuti != null){
            return redirect()->back()->withErrors(['pegawai_id'=>'pegawai sudah pernah diinput, silahkan pembaruan']);
        }
        $saldoCuti = new PegawaiSaldoCuti();
        $saldoCuti->pegawai_id = $request->pegawai_id;
        $saldoCuti->saldo_n = $request->saldo_n;
        $saldoCuti->saldo_n_1 = $request->saldo_n_1;
        $saldoCuti->saldo_n_2 = $request->saldo_n_2;
        try {
            $saldoCuti->save();
            return redirect()->back()->with('success','saldo cuti pegawai berhasil disimpan');
        }catch (QueryException $e){
            return redirect()->back()->withErrors(['query'=>'saldo cuti pegawai gagal disimpan']);
        }
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
        $pegawaiSaldoCutiDetail = PegawaiSaldoCuti::where('id',$id)->first();
        if ($pegawaiSaldoCutiDetail == null){
            return redirect()->withErrors(['query','hari libur tidak ditemukan']);
        }
        return Inertia::render('Pegawai/PegawaiSaldoCuti/Edit',[
            'title'=>'Edit Saldo Pegawai',
            'pegawai'=>Pegawai::getAllDataPegawai(),
            'pegawaiSaldoCutiDetail'=> $pegawaiSaldoCutiDetail
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'pegawai_id'=>['required','exists:pegawai,id'],
            'saldo_n'=>['required','numeric','digits:2'],
            'saldo_n_1'=>['required','numeric','digits:1'],
            'saldo_n_2'=>['required','numeric','digits:1'],

        ],[
            'pegawai_id.required'=>'pegawai harus diisi',
            'pegawai_id.exists'=>'pegawai tidak valid',
            'saldo_n.required'=>'saldo harus diisi',
            'saldo_n.numeric'=>'saldo harus berupa angka',
            'saldo_n.digits'=>'saldo maksimal 2 digit',
            'saldo_n_1.required'=>'saldo harus diisi',
            'saldo_n_1.numeric'=>'saldo harus berupa angka',
            'saldo_n_1.digits'=>'saldo maksimal 1 digit',
            'saldo_n_2.required'=>'saldo harus diisi',
            'saldo_n_2.numeric'=>'saldo harus berupa angka',
            'saldo_n_2.digits'=>'saldo maksimal 1 digit'
        ]);
        $saldoCuti = PegawaiSaldoCuti::where('id',$id)->first();
        $saldoCuti->pegawai_id = $request->pegawai_id;
        $saldoCuti->saldo_n = $request->saldo_n;
        $saldoCuti->saldo_n_1 = $request->saldo_n_1;
        $saldoCuti->saldo_n_2 = $request->saldo_n_2;
        try {
            $saldoCuti->save();
            return redirect()->back()->with('success','saldo cuti pegawai berhasil diubah');
        }catch (QueryException $e){
            return redirect()->back()->withErrors(['query'=>'saldo cuti pegawai gagal diubah']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saldoCuti = PegawaiSaldoCuti::where('id',$id)->first();
        if ($saldoCuti == null){
            return redirect()->back()->withErrors([
                'query' => 'data saldo cuti gagal dihapus'
            ]);
        }
        try {
            $saldoCuti->delete();
            return redirect()->back()->with('success','data hari libur berhasil dihapus');
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database');
            return redirect()->back()->withErrors([
                'query' => 'data anak pegawai gagal dihapus'
            ]);
        }
    }

    public function getDataPegawaiSaldoCuti(Request $request){
        $paginate = ($request->paginate) ? $request->paginate : 10;
        $pegawaiSaldoCuti = PegawaiSaldoCuti::query()->when($request->cari,function ($query,$cari){
            $query->orWhere(DB::raw("CONCAT(nama_depan,' ',nama_belakang)"),'like',"%{$cari}%");
        })
            ->join('pegawai','pegawai.id','=','pegawai_saldo_cuti.pegawai_id')
            ->select('pegawai_saldo_cuti.id','saldo_n','saldo_n_1','saldo_n_2',DB::raw('CONCAT(nama_depan," " ,nama_belakang) AS nama_lengkap'))
            ->paginate($paginate);
        return response()->json($pegawaiSaldoCuti);
    }
}
