<?php

use App\Http\Controllers\Siap\UpdatePegawaiController;
use Illuminate\Support\Facades\Route;

Route::prefix('siap')->group(function () {
    Route::put('pegawai/{pegawai}', UpdatePegawaiController::class)->name('siap.pegawai.update');
});
