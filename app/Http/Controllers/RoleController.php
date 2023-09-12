<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoleController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can:role list', ['only' => ['index', 'show']]);
        // $this->middleware('can:role create', ['only' => ['create', 'store']]);
        // $this->middleware('can:role edit', ['only' => ['edit', 'update']]);
        // $this->middleware('can:role delete', ['only' => ['destroy']]);
        // $this->middleware('can:role get', ['only' => ['get']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = (new Role)->newQuery();
        $roles->latest();
        $roles = $roles->paginate(100)->onEachSide(2)->appends(request()->query());
        return Inertia::render('Role/Index', [
            'roles' => $roles,
            'can' => [
                'create' => Auth::user()->can('role create'),
                'edit' => Auth::user()->can('role edit'),
                'delete' => Auth::user()->can('role delete'),

            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::get();

        try {
            return Inertia::render('Role/Create', [
                'title' => 'Tambah Role',
                'permissions' => fn () => Permission::all()
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
            'name' => 'required|unique:roles',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Role sudah ada di database',
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
