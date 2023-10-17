<?php

use App\Http\Controllers\Siasn\FetchAllPnsDataUtamaController;
use App\Http\Controllers\Siasn\FetchPnsDataUtamaController;
use App\Http\Controllers\Siasn\FetchPnsRwPenghargaanController;
use App\Http\Controllers\Siasn\SiasnDownloadFileController;
use App\Http\Controllers\Siasn\SiasnPenghargaanController;
use App\Http\Controllers\Siasn\SiasnPnsController;
use App\Http\Controllers\SyncRiwayatPenghargaanController;
use Illuminate\Support\Facades\Route;

Route::prefix('siasn')->group(function () {
    Route::get('asn', [SiasnPnsController::class, 'index'])->name('siasn-asn.index');
    Route::get('asn/{asn}', [SiasnPnsController::class, 'show'])->name('siasn-asn.show');

    Route::post('asn/data-utama/fetch', FetchPnsDataUtamaController::class)->name('fetch-pns-data-utama');
    Route::post('asn/data-utama/fetch-all', FetchAllPnsDataUtamaController::class)->name('fetch-all-pns-data-utama');

    Route::get('penghargaan/{penghargaan}/fetch', FetchPnsRwPenghargaanController::class)->name('fetch-pns-rw-penghargaan');
    Route::get('penghargaan/fetch-all', SyncRiwayatPenghargaanController::class)->name('fetch-all-rw-penghargaan');
    Route::get('penghargaan', SiasnPenghargaanController::class)->name('siasn-penghargaan.index');

    Route::get('download', SiasnDownloadFileController::class)->name('siasn-download-file');
});
