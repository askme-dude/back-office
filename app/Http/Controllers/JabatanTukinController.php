<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\JenisJabatan;
use App\Models\JabatanFungsional;
use App\Models\JabatanFungsionalUmum;
use App\Models\JabatanStruktural; //Belum ada Tablenya
use App\Models\JabatanTukin;
use App\Models\Tukin;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class JabatanTukinController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:jabatan_tukin_list', ['only' => ['index', 'show']]);
        $this->middleware('can:jabatan_tukin_create', ['only' => ['create', 'store']]);
        $this->middleware('can:jabatan_tukin_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:jabatan_tukin_delete', ['only' => ['destroy']]);
    }

    public function getJenisJabatan(Request $request)
    {
        $jenisJabatan = JenisJabatan::where('nama', 'not like', '%Jabatan Rangkap%')->get();
        return response()->json($jenisJabatan);
    }

    public function getjabatan(Request $request)
    {
        $jenisJabatanId = $request->id;

        if ($jenisJabatanId === "1") {
            $jabatan = JabatanStruktural::orderBy('nama', 'asc')->get();
        } else if ($jenisJabatanId == "2") {
            $jabatan = JabatanFungsional::orderBy('nama', 'asc')->get();
        } else if ($jenisJabatanId == "4") {
            $jabatan = JabatanFungsionalUmum::orderBy('nama', 'asc')->get();
        }

        return response()->json($jabatan);
    }

    public function getTukin(Request $request)
    {
        // $tukin = Tukin::orderBy('grade', 'asc')->get();
        $tukin = Tukin::select('id', 'grade', DB::raw("FORMAT(nominal, 0) as nominal"))->get();
        return response()->json($tukin);
    }


    public function getNominal(Request $request)
    {
        $nominal = Tukin::select(DB::raw('FORMAT(nominal, 0) as formatted_nominal'))
            ->where('id', $request->id)
            ->first();


        return response()->json($nominal);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $jabatanTukin = DB::table('jabatan_tukin as a')
                    ->select('a.id', 'a.jabatan_id', 'a.jenis_jabatan_id', 'b.nama as jenis_jabatan', 'c.grade', 'c.nominal')
                    ->addSelect(DB::raw('
                        CASE
                            WHEN a.jenis_jabatan_id = 1 THEN d.nama
                            WHEN a.jenis_jabatan_id = 2 THEN e.nama
                            WHEN a.jenis_jabatan_id = 4 THEN f.nama
                            ELSE NULL
                        END AS nama_jabatan
                    '))
                    ->join('jenis_jabatan as b', 'a.jenis_jabatan_id', '=', 'b.id')
                    ->join('tukin as c', 'a.tukin_id', '=', 'c.id')
                    ->leftJoin('jabatan_struktural as d', 'd.id', '=', 'a.jabatan_id')
                    ->leftJoin('jabatan_fungsional as e', 'e.id', '=', 'a.jabatan_id')
                    ->leftJoin('jabatan_fungsional_umum as f', 'f.id', '=', 'a.jabatan_id')
                    ->get();

            return Inertia::render('JabatanTukin/Index', [
            'jabatanTukin' => $jabatanTukin,
            'title' => 'Daftar Tunjangan Kinerja Berdasarkan Jabatan'
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load index data jabatan tukin :' . $e->getMessage());
                return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {

            return Inertia::render('JabatanTukin/Create', [
                'title' => 'Tambah Jabatan Tukin',
            ]);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika load create data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'Create data Jabatan Tukin gagal'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'jenis_jabatan_id' => 'required|unique:jabatan_tukin,jenis_jabatan_id,' . null . ',id,jabatan_id,' . $request->jabatan_id . ',tukin_id,' . $request->tukin_id,
            'jabatan_id' => ['required', 'numeric'],
            'tukin_id' => ['required', 'numeric', 'min:1']
        ], [
            'jenis_jabatan_id.required' => 'Silakan Pilih Jensi Jabatan',
            'jenis_jabatan_id.unique' => 'Jenis Jabatan, Nama Jabatan dan Grade Tunjangan Kinerja Jabatan sudah ada!',
            'jabatan_id.required' => 'Silakan Pilih Jabatan',
            'tukin_id.required' => 'Silakan Pilih Grade Tukin',

        ]);

        try {
            JabatanTukin::create($data);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data Jabatan Tukin gagal disimpan'
            ]);
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
    public function edit(JabatanTukin $jabatanTukin)
    {
        try {
            return Inertia::render('JabatanTukin/Edit',[
                'title' => 'Edit Tunjangan Kinerja Berdasarkan Jabatan',
                'jabatanTukin' => $jabatanTukin
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data jabatan tukin :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JabatanTukin $jabatanTukin)
    {
        // dd($request);
        $data = $request->validate([
            'jenis_jabatan_id' => 'required|unique:jabatan_tukin,jenis_jabatan_id,' . $request->input('jenis_jabatan_id') . ',id,jabatan_id,' . $request->jabatan_id . ',tukin_id,' . $request->tukin_id,
            'jabatan_id' => ['required', 'numeric'],
            'tukin_id' => ['required', 'numeric', 'min:1']
        ], [
            'jenis_jabatan_id.required' => 'Silakan Pilih Jensi Jabatan',
            'jenis_jabatan_id.unique' => 'Jenis Jabatan, Nama Jabatan dan Grade Tunjangan Kinerja Jabatan sudah ada!',
            'jabatan_id.required' => 'Silakan Pilih Jabatan',
            'tukin_id.required' => 'Silakan Pilih Grade Tukin',

        ]);


        try {
            $jabatanTukin->update($data);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika update data jabatan tukin :' . $e->getMessage());
             return redirect()->back()->withErrors([
                    'query' => 'data jabatan tukin gagal diupdate'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JabatanTukin $jabatanTukin)
    {
        try {
            $jabatanTukin->delete();
            return back()->with('toast', ['message' => 'Data berhasil dihapus']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database ketika delete data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'data jabatan tukin gagal dihapus'
                ]);
        }


    }
}
