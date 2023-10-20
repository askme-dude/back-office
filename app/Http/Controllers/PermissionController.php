<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:hak_akses_list', ['only' => ['index', 'show']]);
        $this->middleware('can:hak_akses_create', ['only' => ['create', 'store']]);
        $this->middleware('can:hak_akses_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:hak_akses_delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = (new Permission)->newQuery();
        $permissions->latest();
        $permissions = $permissions->paginate(100)->onEachSide(2)->appends(request()->query());
        return Inertia::render('Permission/Index', [
            'permissions' => $permissions,
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('Permission/Create', [
                'title' => 'Tambah Hak Akses',
            ]);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika load create data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'Load data gagal'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:permissions',
            'guard_name' => ['required'],
        ], [
            'name.required' => 'Nama Hak Akses harus diisi',
            'name.unique' => 'Hak Akses sudah ada di database',
            'guard_name.required' => 'Jenis Hak Akses harus diisi'
        ]);

        try {
            Permission::create($data);
            // return redirect()->route('permission.index')->with('toast', ['message', 'Data berhasil disimpan']);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data hak akses gagal disimpan'
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
    public function edit(Permission $permission)
    {
        try {
            return Inertia::render('Permission/Edit', [
                'title' => 'Ubah Hak Akses',
                'permission' => $permission,
            ]);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'Load data gagal'
            ]);
        }
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|unique:permissions,name,' . $request->input('id') . ',id',
            'guard_name' => ['required'],
        ], [
            'name.required' => 'Nama Hak Akses harus diisi',
            'name.unique' => 'Hak Akses sudah ada di database',
            'guard_name.required' => 'Jenis Hak Akses harus diisi'
        ]);

        try {
            $permission->update($data);
            return redirect()->route('permission.index')->with('toast', ['message', 'Data berhasil diubah']);
            // return back()->with('toast', ['message' => 'Data berhasil disimpan']);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika update data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data Hak Akses gagal diupdate'
            ]);
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return back()->with('toast', ['message' => 'Data berhasil dihapus']);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database ketika delete data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data permission gagal dihapus'
            ]);
        }
    }
}
