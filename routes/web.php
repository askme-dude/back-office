<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TukinController;
use App\Http\Controllers\UangMakanController;
use App\Http\Controllers\Pegawai\PegawaiAlamatController;
use \App\Http\Controllers\Master\HirarkiUnitKerjaController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('pegawai')->group(function (){
    Route::get('alamat/getdata',[PegawaiAlamatController::class,'getDataPegawaiAlamat'])->name('alamat.getdata');
    Route::resource('alamat',PegawaiAlamatController::class)->only('index','create','store','edit','update','destroy','show');
});
Route::prefix('master')->group(function (){
    Route::get('hirarki-unit-kerja/getdata',[HirarkiUnitKerjaController::class,'getDataHirarkiUnitKerja'])->name('hirarki-unit-kerja.getdata');
    Route::resource('hirarki-unit-kerja',HirarkiUnitKerjaController::class)->except('show');
    
    Route::resource('tukin', TukinController::class)->only('index','create','store','edit','update','destroy');
    Route::get('tukin/getdatatable',[TukinController::class,'getDataTable'])->name('tukin.getdatatable');
    Route::resource('umak', UangMakanController::class)->only('index','create','store','edit','update','destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
