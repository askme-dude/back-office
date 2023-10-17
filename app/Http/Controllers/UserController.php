<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:pengguna_list', ['only' => ['index', 'show']]);
        $this->middleware('can:pengguna_create', ['only' => ['create', 'store']]);
        $this->middleware('can:pengguna_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:pengguna_delete', ['only' => ['destroy']]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = (new User)->newQuery();
        $users->latest();
        $users = $users->paginate(100)->onEachSide(2)->appends(request()->query());
        return Inertia::render('User/Index', [
            'users' => $users,
            'can' => [
                'create' => Auth::user()->can('pengguna_create'),
                'edit' => Auth::user()->can('pengguna_edit'),
                'delete' => Auth::user()->can('pengguna_delete'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // try {
        //     return Inertia::render('User/Create', [
        //         'title' => 'Tambah User',
        //         'role' => fn () =>   Role::get(),
        //         'can' => [
        //             'create' => Auth::user()->can('pengguna_reate'),
        //             'edit' => Auth::user()->can('pengguna_edit'),
        //             'delete' => Auth::user()->can('pengguna_delete'),
        //         ]
        //     ]);
        // } catch (QueryException $e) {
        //     Log::error('terjadi kesalahan pada koneksi database  ketika load create data :' . $e->getMessage());
        //     return redirect()->back()->withErrors([
        //         'query' => 'Load data gagal'
        //     ]);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
