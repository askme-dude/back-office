<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:role_list', ['only' => ['index', 'show']]);
        $this->middleware('can:role_create', ['only' => ['create', 'store']]);
        $this->middleware('can:role_edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:role_delete', ['only' => ['destroy']]);
        $this->middleware('can:role_get', ['only' => ['get']]);
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
            'title'=> 'Role',
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
            'name' => 'required|unique:roles,name,' . null . ',id',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Role sudah ada di database',
        ]);

        try {

            $role = Role::create(['name' => $request->name]);
            $permissionIds = $request->hak_akses_value;
            for ($index = 0; $index < count($permissionIds); $index++) {
                $element = $permissionIds[$index];
                if ($element === true) {
                    $permission = Permission::find($index+1);
                    if ($permission) {
                        // Assign the permission to the role
                        $role->givePermissionTo($permission);
                    }
                }
            }

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
        try {
            $roleWithPermissions=[];
            $role = Role::find($id);
            $permissions = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();

            for ($i = 0; $i < count($permissions); $i++) {
                $permission = $permissions[$i];
                $bln = false;
                foreach ($rolePermissions as $rp) {


                    if ($rp==$permission["id"]){
                        $roleWithPermissions[$i]=true;
                        $bln=true;
                        break;

                    }
                }
                if($bln==false){
                    $roleWithPermissions[$i]=false;
                }

            }

            return Inertia::render('Role/Edit', [
                'title' => 'Ubah Role',
                'role'=> $role,
                'permissions' => $permissions,
                'roleWithPermissions' => $roleWithPermissions
            ]);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika load edit data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'Load data gagal'
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'hak_akses_value' => 'required|array|min:1',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Role sudah ada di database',
            'hak_akses_value.required' => 'Mohon pilih hak akses',
        ]);

        try {
            $role->update($data);

            DB::table('role_has_permissions')
                ->where('role_id',$role->id)
                ->delete();

            $permissionIds = $request->hak_akses_value;
            for ($index = 0; $index < count($permissionIds); $index++) {
                $element = $permissionIds[$index];
                if ($element === true) {
                    $permission = Permission::find($index+1);
                    if ($permission) {
                        // Assign the permission to the role
                        $role->givePermissionTo($permission);
                    }
                }
            }

            // return redirect()->route('permission.index')->with('toast', ['message', 'Data berhasil disimpan']);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika simpan data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data hak akses gagal disimpan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {

            var_dump('test');
            DB::table('role_has_permissions')
                ->where('role_id',$role->id)
                ->delete();

            $role->delete();
            // return back()->with('toast', ['message' => 'Data berhasil dihapus']);
            // return redirect()->route('permission.index')->with('toast', ['message', 'Data berhasil disimpan']);
        } catch (QueryException $e) {
            Log::error('terjadi kesalahan pada koneksi database  ketika delete data :' . $e->getMessage());
            return redirect()->back()->withErrors([
                'query' => 'data hak akses gagal dihapus'
            ]);
        }

    }
}
