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
use App\Models\JabatanUnitKerja;
use App\Models\Tukin;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class JabatanUnitKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:jabatan_tukin_list', ['only' => ['index', 'show']]);
        $this->middleware('can:jabatan_tukin_create', ['only' => ['create', 'store']]);
        $this->middleware('can:jabatan_tukin_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:jabatan_tukin_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $result = DB::table('jabatan_unit_kerja AS x')
                ->select('x.id', 'x.jabatan_tukin_id', 'z.jenis_jabatan', 'z.nama_jabatan','z.grade', 'z.nominal', 'y.nama_unit_kerja', 'x.hirarki_unit_kerja_id')
                ->joinSub(function ($query) {
                    $query->select('a.id', 'a.child_unit_kerja_id', 'a.parent_unit_kerja_id', 'b.nama AS nama_unit_kerja', 'c.nama_jenis_unit_kerja', 'c.nama_parent_unit_kerja')
                        ->from('hirarki_unit_kerja AS a')
                        ->join('unit_kerja AS b', 'a.child_unit_kerja_id', '=', 'b.id')
                        ->joinSub(function ($query) {
                            $query->select('a.id', 'a.child_unit_kerja_id', 'a.parent_unit_kerja_id', 'c.nama AS nama_jenis_unit_kerja', 'b.nama AS nama_parent_unit_kerja')
                                ->from('hirarki_unit_kerja AS a')
                                ->join('unit_kerja AS b', 'a.parent_unit_kerja_id', '=', 'b.id')
                                ->join('jenis_unit_kerja AS c', 'c.id', '=', 'b.jenis_unit_kerja_id');
                        }, 'c', 'a.id', '=', 'c.id');
                }, 'y', 'x.hirarki_unit_kerja_id', '=', 'y.id')
                ->joinSub(function ($query) {
                    $query->select('a.id', 'a.jabatan_id', 'a.jenis_jabatan_id', 'b.nama AS jenis_jabatan', 'c.grade', 'c.nominal')
                        ->addSelect(DB::raw('
                            CASE
                                WHEN a.jenis_jabatan_id = 1 THEN d.nama
                                WHEN a.jenis_jabatan_id = 2 THEN e.nama
                                WHEN a.jenis_jabatan_id = 4 THEN f.nama
                                ELSE NULL
                            END AS nama_jabatan
                        '))
                        ->from('jabatan_tukin AS a')
                        ->join('jenis_jabatan AS b', 'a.jenis_jabatan_id', '=', 'b.id')
                        ->join('tukin AS c', 'a.tukin_id', '=', 'c.id')
                        ->leftJoin('jabatan_struktural AS d', 'd.id', '=', 'a.jabatan_id')
                        ->leftJoin('jabatan_fungsional AS e', 'e.id', '=', 'a.jabatan_id')
                        ->leftJoin('jabatan_fungsional_umum AS f', 'f.id', '=', 'a.jabatan_id');
                }, 'z', 'x.jabatan_tukin_id', '=', 'z.id')
                ->get();

            return Inertia::render('JabatanUnitKerja/Index', [
            'jabatanUnitKerja' => $result,
            'title' => 'Jabatan Unit kerja'
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load index data :' . $e->getMessage());
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
                ->orderBy('nama_jabatan')
                ->get();

            //Get Data Hierarki Unit Kerja
            $hirarkiUnitKerja = DB::table('db_backoffice.hirarki_unit_kerja as a')
                ->select('a.id', 'a.child_unit_kerja_id', 'a.parent_unit_kerja_id', 'b.nama as nama_unit_kerja', 'c.nama_jenis_unit_kerja', 'c.nama_parent_unit_kerja')
                ->join('unit_kerja as b', 'a.child_unit_kerja_id', '=', 'b.id')
                ->join(DB::raw('(SELECT a.id, a.child_unit_kerja_id, a.parent_unit_kerja_id, c.nama as nama_jenis_unit_kerja, b.nama as nama_parent_unit_kerja
                        FROM db_backoffice.hirarki_unit_kerja a
                        INNER JOIN unit_kerja b ON a.parent_unit_kerja_id = b.id
                        INNER JOIN jenis_unit_kerja c ON c.id = b.jenis_unit_kerja_id) c'), 'a.id', '=', 'c.id')
                ->orderBy('b.nama', 'asc')
                ->get();

            return Inertia::render('JabatanUnitKerja/Create', [
                'title' => 'Tambah Jabatan Unit Kerja',
                'jabatanTukin' => $jabatanTukin,
                'hirarkiUnitKerja' => $hirarkiUnitKerja,
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
            'jabatan_tukin_id' => 'required|unique:jabatan_unit_kerja,jabatan_tukin_id,' . null . ',id,hirarki_unit_kerja_id,' . $request->hirarki_unit_kerja_id,
            'hirarki_unit_kerja_id' => ['required', 'numeric']
        ], [
            'jabatan_tukin_id.required' => 'Silakan Pilih Jabatan',
            'jabatan_tukin_id.unique' => 'Jabatan dan Unit Kerja sudah ada!',
            'hirarki_unit_kerja_id.required' => 'Silakan Pilih Unit Kerja',
        ]);

        try {
            JabatanUnitKerja::create($data);

        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data jabatan unit kerja :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data Jabatan unit kerja gagal disimpan'
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
    public function edit(JabatanUnitKerja $jabatanUnitKerja)
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
            ->orderBy('nama_jabatan')
            ->get();

        //Get Data Hierarki Unit Kerja
        $hirarkiUnitKerja = DB::table('db_backoffice.hirarki_unit_kerja as a')
            ->select('a.id', 'a.child_unit_kerja_id', 'a.parent_unit_kerja_id', 'b.nama as nama_unit_kerja', 'c.nama_jenis_unit_kerja', 'c.nama_parent_unit_kerja')
            ->join('unit_kerja as b', 'a.child_unit_kerja_id', '=', 'b.id')
            ->join(DB::raw('(SELECT a.id, a.child_unit_kerja_id, a.parent_unit_kerja_id, c.nama as nama_jenis_unit_kerja, b.nama as nama_parent_unit_kerja
                    FROM db_backoffice.hirarki_unit_kerja a
                    INNER JOIN unit_kerja b ON a.parent_unit_kerja_id = b.id
                    INNER JOIN jenis_unit_kerja c ON c.id = b.jenis_unit_kerja_id) c'), 'a.id', '=', 'c.id')
            ->orderBy('b.nama', 'asc')
            ->get();

            return Inertia::render('JabatanUnitKerja/Edit', [
                'title' => 'Edit Jabatan Unit Kerja',
                'jabatanUnitKerja' => $jabatanUnitKerja,
                'jabatanTukin' => $jabatanTukin,
                'hirarkiUnitKerja' => $hirarkiUnitKerja,
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data jabatan unit kerja :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JabatanUnitKerja $jabatanUnitKerja)
    {
        $data = $request->validate([
            'jabatan_tukin_id' => 'required|unique:jabatan_unit_kerja,jabatan_tukin_id,' .  $request->input('jabatan_tukin_id')  . ',id,hirarki_unit_kerja_id,' . $request->hirarki_unit_kerja_id,
            'hirarki_unit_kerja_id' => ['required', 'numeric']
        ], [
            'jabatan_tukin_id.required' => 'Silakan Pilih Jabatan',
            'jabatan_tukin_id.unique' => 'Jabatan dan Unit Kerja sudah ada!',
            'hirarki_unit_kerja_id.required' => 'Silakan Pilih Unit Kerja',
        ]);

        try {
            $jabatanUnitKerja->update($data);
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
    public function destroy(JabatanUnitKerja $jabatanUnitKerja)
    {
        try {
            $jabatanUnitKerja->delete();
            return back()->with('toast', ['message' => 'Data berhasil dihapus']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database ketika delete data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'data jabatan tukin gagal dihapus'
                ]);
        }


    }
}
