<?php

namespace App\Http\Controllers;

use App\Models\HirarkiUnitKerja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HirarkiUnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = 10;
        if ($request->paginate){
            $paginate = $request->paginate;
        }
        $hirarkiUnitKerja = HirarkiUnitKerja::query()->when($request->cari,function ($query,$cari){
            $query->orWhere('parent.nama','like',"%{$cari}%");
            $query->orWhere('child.nama','like',"%{$cari}%");
        })
            ->join('unit_kerja as child','hirarki_unit_kerja.child_unit_kerja_id','=','child.id')
            ->join('unit_kerja as parent','hirarki_unit_kerja.parent_unit_kerja_id','=','parent.id')
            ->select('hirarki_unit_kerja.id','child.nama as nama_child','parent.nama as nama_parent')
            ->orderBy('hirarki_unit_kerja.id','ASC')
            ->paginate($paginate);
        return Inertia::render('HirarkiUnitKerja/Index',[
            'hirarkiUnitKerja' =>$hirarkiUnitKerja,
            'title'=>'Hirarki Unit Kerja',
            'paginate'=>$paginate
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
