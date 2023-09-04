<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiAlamatRequest;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Pegawai;
use App\Models\PegawaiAlamat;
use App\Models\Propinsi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PegawaiAlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Pegawai/PegawaiAlamat/Index',[
            'title' => 'Alamat',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kota = [];
        $kecamatan = [];
        $desa = [];
        $pegawai = Pegawai::select('id','nama_depan','nama_belakang',DB::raw('CONCAT(nama_depan," " ,nama_belakang) AS nama_lengkap'),)->whereNull('tanggal_berhenti')->get();
        $propinsi = Propinsi::select('id','nama')->get();
        if ($request->propinsi_id){
            $kota = Kota::select('id','nama','propinsi_id')->where('propinsi_id',$request->propinsi_id)->get();
        }
        if ($request->kota_id){
            $kecamatan = Kecamatan::select('id','nama','kota_id')->where('kota_id',$request->kota_id)->get();
        }
        if ($request->kecamatan_id){
            $desa = Desa::select('id','nama','kecamatan_id')->where('kecamatan_id',$request->kecamatan_id)->get();
        }

        return Inertia::render('Pegawai/PegawaiAlamat/Create',[
            'title'=>'Tambah Alamat',
            'pegawai'=>fn()=>$pegawai,
            'propinsi' => fn()=>$propinsi,
            'kota' =>fn()=>$kota,
            'kecamatan' =>fn()=>$kecamatan,
            'desa' =>fn()=>$desa,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PegawaiAlamatRequest $request)
    {
        $countPegawai = PegawaiAlamat::where('pegawai_id',$request->pegawai_id)->count();
        if($countPegawai >= 2){
            return redirect()->back()->withErrors([
                'pegawai_id' => 'Pegawai sudah memiliki dua alamat'
            ]);
        }
        $alamat = new PegawaiAlamat();
        $alamat->pegawai_id = $request->pegawai_id;
        $alamat->tipe_alamat = $request->tipe_alamat;
        $alamat->propinsi_id = $request->propinsi_id;
        $alamat->kota_id = $request->kota_id;
        $alamat->kecamatan_id = $request->kecamatan_id;
        $alamat->desa_id = $request->desa_id;
        $alamat->kode_pos = $request->kode_pos;
        $alamat->alamat = $request->alamat;
        try {
            $alamat->save();
            return redirect()->back()->with('success','Data alamat berhasil disimpan');
        }catch (QueryException $e){
            Log::info('terjadi kesalahan pada query atau koneksi database');
             return redirect()->back()->withErrors(['query' => 'data alamat gagal disimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pegawaiAlamatDetail = PegawaiAlamat::join('pegawai','pegawai_alamat.pegawai_id','=','pegawai.id')
            ->join('propinsi','pegawai_alamat.propinsi_id', '=','propinsi.id')
            ->join('kota','pegawai_alamat.kota_id', '=','kota.id')
            ->join('kecamatan','pegawai_alamat.kecamatan_id', '=','kecamatan.id')
            ->join('desa','pegawai_alamat.desa_id', '=','desa.id')
            ->select('pegawai_alamat.id','pegawai_alamat.tipe_alamat',
                'pegawai_alamat.kode_pos','pegawai_alamat.alamat',
                'pegawai.nama_belakang','pegawai.nama_depan',
                'propinsi.nama as nama_propinsi','kota.nama as nama_kota',
                'kecamatan.nama as nama_kecamatan','desa.nama as nama_desa')
            ->where('pegawai_alamat.id',$id)->first();
        return response()->json($pegawaiAlamatDetail);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,string $alamat)
    {
        $pegawaiAlamat = PegawaiAlamat::where('id',$alamat)->first();
        if ($pegawaiAlamat == null){
            return redirect()->back()->withErrors(['query' => 'alamat pegawai tidak valid atau tidak ditemukan']);return redirect()->back()->withErrors(['query' => 'tidak dapat menampilkan form edit']);
        }
        $kota = Kota::select('id','nama','propinsi_id')->where('propinsi_id',$pegawaiAlamat->propinsi_id)->get();
        $kecamatan = Kecamatan::select('id','nama','kota_id')->where('kota_id',$pegawaiAlamat->kota_id)->get();
        $desa = Desa::select('id','nama','kecamatan_id')->where('kecamatan_id',$pegawaiAlamat->kecamatan_id)->get();
        $pegawai = Pegawai::where('id',$pegawaiAlamat->pegawai_id)->select('nama_depan','nama_belakang')->first();
        $propinsi = Propinsi::select('id','nama')->get();
        if ($request->propinsi_id){
            $kota = Kota::select('id','nama','propinsi_id')->where('propinsi_id',$request->propinsi_id)->get();
        }
        if ($request->kota_id){
            $kecamatan = Kecamatan::select('id','nama','kota_id')->where('kota_id',$request->kota_id)->get();
        }
        if ($request->kecamatan_id){
            $desa = Desa::select('id','nama','kecamatan_id')->where('kecamatan_id',$request->kecamatan_id)->get();
        }
        return Inertia::render('Pegawai/PegawaiAlamat/Edit',[
            'pegawaiAlamat' => $pegawaiAlamat,
            'title'=>'Ubah Alamat',
            'pegawai'=>fn()=>$pegawai,
            'propinsi' => fn()=>$propinsi,
            'kota' =>fn()=>$kota,
            'kecamatan' =>fn()=>$kecamatan,
            'desa' =>fn()=>$desa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PegawaiAlamatRequest $request, string $id)
    {
        $alamat = PegawaiAlamat::where('id',$id)->first();
        $alamat->pegawai_id = $request->pegawai_id;
        $alamat->tipe_alamat = $request->tipe_alamat;
        $alamat->propinsi_id = $request->propinsi_id;
        $alamat->kota_id = $request->kota_id;
        $alamat->kecamatan_id = $request->kecamatan_id;
        $alamat->desa_id = $request->desa_id;
        $alamat->kode_pos = $request->kode_pos;
        $alamat->alamat = $request->alamat;
        try {
            $alamat->save();
            return redirect()->back()->with('success','Data alamat berhasil diubah');
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database');
            return redirect()->back()->withErrors([
                'query' => 'data alamat gagal disimpan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pegawaiAlamat = PegawaiAlamat::where('id',$id)->first();
        if ($pegawaiAlamat ==null){
            return redirect()->back()->withErrors([
                'query' => 'data alamat gagal dihapus'
            ]);
        }
        try {
            $pegawaiAlamat->delete();
            return redirect()->back()->with('success','data alamat berhasil dihapus');
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database');
            return redirect()->back()->withErrors([
                'query' => 'data alamat gagal dihapus'
            ]);
        }
    }

    /**
     * GET DATA ALAMAT PEGAWAI
     */
    public function getDataPegawaiAlamat(Request $request){
        if ($request->paginate){
            $paginate = $request->paginate;
        }else{
            $paginate = 10;
        }
        $pegawaiAlamat = PegawaiAlamat::query()->when($request->cari,function ($query,$cari){
            $query->orWhere('nama_depan','like',"%{$cari}%");
            $query->orWhere('nama_belakang','like',"%{$cari}%");
            $query->orWhere('propinsi.nama','like',"%{$cari}%");
            $query->orWhere('kota.nama','like',"%{$cari}%");
            $query->orWhere('kecamatan.nama','like',"%{$cari}%");
        })
            ->whereNull('tanggal_berhenti')
            ->join('pegawai','pegawai_alamat.pegawai_id','=','pegawai.id')
            ->join('propinsi','pegawai_alamat.propinsi_id', '=','propinsi.id')
            ->join('kota','pegawai_alamat.kota_id', '=','kota.id')
            ->join('kecamatan','pegawai_alamat.kecamatan_id', '=','kecamatan.id')
            ->join('desa','pegawai_alamat.desa_id', '=','desa.id')
            ->select('pegawai_alamat.id','pegawai_alamat.tipe_alamat','pegawai_alamat.kode_pos','pegawai_alamat.alamat',
                'pegawai.nama_belakang','pegawai.nama_depan',
                'propinsi.nama as nama_propinsi','kota.nama as nama_kota',
                'kecamatan.nama as nama_kecamatan','desa.nama as nama_desa')
            ->paginate($paginate);

        return response()->json($pegawaiAlamat);
    }
}
