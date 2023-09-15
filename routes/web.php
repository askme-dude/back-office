<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\UangMakanController;
use App\Http\Controllers\Pegawai\PegawaiAlamatController;
use App\Http\Controllers\Pegawai\PegawaiRiwayatDiklatController;
use \App\Http\Controllers\Master\HirarkiUnitKerjaController;
use \App\Http\Controllers\Pegawai\PegawaiTmtGajiController;
use \App\Http\Controllers\Pegawai\PegawaiRiwayatPendidikanController;
use \App\Http\Controllers\Master\KotaController;
use \App\Http\Controllers\Pegawai\PegawaiAnakController;
use \App\Http\Controllers\Pegawai\PegawaiSuamiIstriController;
use \App\Http\Controllers\Master\HariLiburController;
use \App\Http\Controllers\Auth\LdapController;
use \App\Http\Controllers\Pegawai\PegawaiSaldoCutiController;
use \App\Http\Controllers\Pegawai\PegawaiCutiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('tukin', TukinController::class)->only('index','create','store','edit','update','destroy');
Route::resource('umak', UangMakanController::class)->only('index','create','store','edit','update','destroy');

Route::prefix('pegawai')->group(function (){
    Route::get('alamat/getdata',[PegawaiAlamatController::class,'getDataPegawaiAlamat'])->name('alamat.getdata');
    Route::resource('alamat',PegawaiAlamatController::class)->only('index','create','store','edit','update','destroy','show');
    Route::get('riwayat-diklat/getdata',[PegawaiRiwayatDiklatController::class,'getDataRiwayatDiklat'])->name('riwayat-diklat.getdata');
    Route::resource('riwayat-diklat',PegawaiRiwayatDiklatController::class);
    Route::get('tmt-gaji/getdata',[PegawaiTmtGajiController::class,'getDataTmtGaji'])->name('tmt-gaji.getdata');
    Route::resource('tmt-gaji',PegawaiTmtGajiController::class)->except('show');
    Route::get('riwayat-pendidikan/getdata',[PegawaiRiwayatPendidikanController::class,'getDataRiwayatPendidikan'])->name('riwayat-pendidikan.getdata');
    Route::resource('riwayat-pendidikan',PegawaiRiwayatPendidikanController::class);
    Route::get('anak/getdata',[PegawaiAnakController::class,'getDataPegawaiAnak'])->name('anak.getdata');
    Route::resource('anak',PegawaiAnakController::class);
    Route::get('suami-istri/getdata',[PegawaiSuamiIstriController::class,'getDataPegawaiSuamiIstri'])->name('suami-istri.getdata');
    Route::resource('suami-istri',PegawaiSuamiIstriController::class);
    Route::get('saldo-cuti/getdata',[PegawaiSaldoCutiController::class,'getDataPegawaiSaldoCuti'])->name('saldo-cuti.getdata');
    Route::resource('saldo-cuti',PegawaiSaldoCutiController::class)->except('show','destroy');
    Route::get('/cuti/pengajuan',[PegawaiCutiController::class,'pengajuan'])->name('pegawai-cuti.pengajuan');
});
Route::prefix('master')->group(function (){
    Route::post('kota/getdata',[KotaController::class,'getKota'])->name('kota.getdata');
    Route::get('hirarki-unit-kerja/getdata',[HirarkiUnitKerjaController::class,'getDataHirarkiUnitKerja'])->name('hirarki-unit-kerja.getdata');
    Route::resource('hirarki-unit-kerja',HirarkiUnitKerjaController::class)->except('show');
    Route::get('hari-libur/getdata',[HariLiburController::class,'getDataHariLibur'])->name('hari-libur.getdata');
    Route::resource('hari-libur',HariLiburController::class)->except('show');
});

//Route::middleware('auth')->group(function () {
//    Route::get('', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
Route::middleware('guest')->group(function (){
    Route::get('/login/ldap', [LdapController::class, 'showLoginForm'])->name('loginldap.show');
    Route::post('/login/ldap', [LdapController::class, 'login'])->name('login.ldap');
});
Route::get('/logout/ldap', [LdapController::class, 'logout'])->name('logout.ldap');



