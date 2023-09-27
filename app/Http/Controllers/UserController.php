<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use DB;

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
        // $users = (new User)->newQuery();
        // $users->latest();
        // $users = $users->paginate(100)->onEachSide(2)->appends(request()->query());
        // return Inertia::render('User/Index', [
        //     'users' => $users,
        //     'can' => [
        //         'create' => Auth::user()->can('pengguna_create'),
        //         'edit' => Auth::user()->can('pengguna_edit'),
        //         'delete' => Auth::user()->can('pengguna_delete'),
        //     ]
        // ]);
        return Inertia::render('User/Index', [
            'user' => fn() => QueryBuilder::for(User::class)
                ->select('id', 'name', 'email')
                ->allowedFilters(['name', 'email'])
                ->allowedSorts(['name', 'email'])
                ->paginate(request('per_page', 15))
                ->onEachSide(1)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('User/Create', [
                'title' => 'Tambah Pengguna Baru',
                'role' => fn () =>   Role::get(),
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
         // dd($request->all());
         $data = $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email',
            'password' => ['required', 'same:re_password'],
            're_password' => ['required'],
            'role_id' => ['required'],
        ],[
            'name.required'=>'mohon input username',
            'name.unique' => 'username sudah ada, silakan menggunakan username yang lain',
            'email.required' => 'mohon input alamat email',
            'email.unique' => 'email sudah terdaftar silakan menggunakan email yang lain',
            'password.same' => 'Password dan Konfirmasi Password harus sama',
        ]);

        try {
            $role = Role::find($request->role_id);
            $user=User::create($data);
            $user->assignRole($role);
            // return redirect()->route('gaji.index')->with('toast', ['message', 'Data berhasil disimpan']);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'data pengguna gagal disimpan'
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
    public function edit(User $user)
    {
        try {
            $roleIds = $user->roles->pluck('id');

            return Inertia::render('User/Edit',[
                'title' => 'Ubah Data Pengguna',
                'user' => $user,
                'role' => fn () => Role::all(),
                'role_id' =>$roleIds,
            ]);
        }catch (QueryException $e){
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                    'query' => 'Load data gagal'
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // dd($request);
        $data = $request->validate([
            'name' => 'required|unique:users,name,' . $user->id,
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'same:re_password',
            'role_id' => 'required',
        ],[
            'name.required'=>'mohon input username',
            'name.unique' => 'username sudah ada, silakan menggunakan username yang lain',
            'email.required' => 'mohon input alamat email',
            'email.unique' => 'email sudah terdaftar silakan menggunakan email yang lain',
            'password.same' => 'Password dan Konfirmasi Password harus sama',
        ]);

        try {
            $role = Role::find($request->role_id);
            $user->update($data);
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();

            $user->assignRole($role);

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
    public function destroy(string $id)
    {
        //
    }
}
