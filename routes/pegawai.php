<?php

use App\Http\Controllers\Pegawai\PegawaiAlamatController;
use App\Http\Controllers\Pegawai\PegawaiAnakController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Pegawai\PegawaiRiwayatDiklatController;
use App\Http\Controllers\Pegawai\PegawaiRiwayatJabatanController;
use App\Http\Controllers\Pegawai\PegawaiRiwayatPendidikanController;
use App\Http\Controllers\Pegawai\PegawaiSaldoCutiController;
use App\Http\Controllers\Pegawai\PegawaiSuamiIstriController;
use App\Http\Controllers\Pegawai\PegawaiTmtGajiController;
use Illuminate\Support\Facades\Route;

Route::resource('pegawai', PegawaiController::class);
Route::resource('alamat', PegawaiAlamatController::class)->except('show');
Route::resource('riwayat-diklat', PegawaiRiwayatDiklatController::class)->except('show');
Route::get('riwayat-pendidikan/getdata', [PegawaiRiwayatPendidikanController::class, 'getDataRiwayatPendidikan'])->name('riwayat-pendidikan.getdata');
Route::resource('riwayat-pendidikan', PegawaiRiwayatPendidikanController::class)->except('show');

Route::prefix('pegawai')->group(function () {
    Route::post('alamat/getkota', [PegawaiAlamatController::class, 'getKota'])->name('alamat.getkota');
    Route::resource('riwayat_jabatan_pegawai', PegawaiRiwayatJabatanController::class)->only('index');
    Route::get('tmt-gaji/getdata', [PegawaiTmtGajiController::class, 'getDataTmtGaji'])->name('tmt-gaji.getdata');
    Route::resource('tmt-gaji', PegawaiTmtGajiController::class)->except('show');
    Route::get('anak/getdata', [PegawaiAnakController::class, 'getDataPegawaiAnak'])->name('anak.getdata');
    Route::resource('anak', PegawaiAnakController::class);
    Route::get('suami-istri/getdata', [PegawaiSuamiIstriController::class, 'getDataPegawaiSuamiIstri'])->name('suami-istri.getdata');
    Route::resource('suami-istri', PegawaiSuamiIstriController::class);
    Route::get('saldo-cuti/getdata', [PegawaiSaldoCutiController::class, 'getDataPegawaiSaldoCuti'])->name('saldo-cuti.getdata');
    Route::resource('saldo-cuti', PegawaiSaldoCutiController::class)->except('show', 'destroy');
});
