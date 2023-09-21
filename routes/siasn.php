<?php

use App\Http\Controllers\Siasn\FetchPnsDataUtamaController;
use App\Http\Controllers\Siasn\SiasnPnsController;
use Illuminate\Support\Facades\Route;

Route::prefix('siasn')->group(function () {
    Route::get('asn', [SiasnPnsController::class, 'index'])->name('siasn.asn.index');
    Route::get('asn/{asn}', [SiasnPnsController::class, 'show'])->name('siasn.asn.show');

    Route::post('asn/data-utama', FetchPnsDataUtamaController::class)->name('fetch-pns-data-utama');
});
