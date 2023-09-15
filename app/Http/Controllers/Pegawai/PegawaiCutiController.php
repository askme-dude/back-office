<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PegawaiCutiController extends Controller
{
    public function pengajuan(){
        return Inertia::render('Pegawai/PegawaiCuti/Index',[
            'title'=>'Pengajuan',
            'jenis_cuti'=> JenisCuti::select('id','jenis')->get()
        ]);
    }
}
