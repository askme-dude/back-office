<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Golongan;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class GajiController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:gaji_list', ['only' => ['index', 'show']]);
        $this->middleware('can:gaji_create', ['only' => ['create', 'store']]);
        $this->middleware('can:gaji_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:gaji_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $gaji = Gaji::select('gaji.id as id','gaji.masa_kerja as masa_kerja', 'gaji.nominal as nominal', 'golongan.nama as golongan')->join('golongan', 'golongan.id', '=', 'gaji.golongan_id')
            ->get();

            return Inertia::render('Gaji/Index', [
            'gaji' => $gaji,
            'title' => 'Gaji'
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load index data :' . $e->getMessage());
             return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Gaji/Create',[
                'title' => 'Tambah Gaji',
                'golongan' => fn () => Golongan::all()
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load create data :' . $e->getMessage());
             return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'masa_kerja' => 'required|unique:gaji,masa_kerja,' . null . ',id,golongan_id,' . $request->input('golongan_id'),
            'golongan_id' => ['required', 'numeric'],
            'nominal' => ['required', 'numeric','min:1']
        ],[
            'golongan_id.required'=>'Golongan harus diisi',
            'masa_kerja.required' => 'Masa Kerja harus diisi',
            'masa_kerja.unique' => 'Golongan dan Masa Kerja sudah',
            'nominal.required' => 'Nominal gaji harus diisi'
        ]);

        try {
            Gaji::create($data);
            // return redirect()->route('gaji.index')->with('toast', ['message', 'Data berhasil disimpan']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'data gaji gagal disimpan'
                ]);
        }
        // return back()->with('toast', ['message' => 'Data berhasil disimpan']);
    }

    public function edit(Gaji $gaji)
    {
        try {
            return Inertia::render('Gaji/Edit',[
                'title' => 'Tambah Gaji',
                'gaji' => $gaji,
                'golongan' => fn () => Golongan::all()
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }

    public function update(Request $request, Gaji $gaji)
    {
        $data = $request->validate([
            'masa_kerja' => 'required|unique:gaji,masa_kerja,' . $request->input('masa_kerja') . ',id,golongan_id,' . $request->input('golongan_id'),
            'golongan_id' => ['required','numeric'],
            'nominal' => ['required', 'numeric']
        ],[
            'golongan_id.required'=>'Golongan harus diisi',
            'masa_kerja.required' => 'Masa Kerja harus diisi',
            'nominal.required' => 'Nominal gaji harus diisi'
        ]);

        try {
            $gaji->update($data);
            return redirect()->route('gaji.index')->with('toast', ['message', 'Data berhasil diubah']);
            // return back()->with('toast', ['message' => 'Data berhasil disimpan']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika update data :' . $e->getMessage());
             return redirect()->back()->withErrors([
                    'query' => 'data gaji gagal diupdate'
                ]);
        }
    }

    public function destroy(Gaji $gaji)
    {
        try {
            $gaji->delete();
            return back()->with('toast', ['message' => 'Data berhasil dihapus']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database ketika delete data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'data gaji gagal dihapus'
                ]);
        }


    }
}
