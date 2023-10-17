<?php

namespace App\Http\Controllers;

use App\Jobs\GetAllSiasnRwPenghargaanJob;
use App\Jobs\SyncRiwayatPenghargaanJob;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use App\Services\SiasnSimpegService;
use Illuminate\Support\Facades\Bus;

class SyncRiwayatPenghargaanController extends Controller
{
    public function __invoke(SiasnSimpegService $service)
    {
        Bus::chain([
            new GetAllSiasnRwPenghargaanJob,
            fn() => SiasnPnsRwPenghargaan::lazyById(100)
                ->each(fn($siasn) => SyncRiwayatPenghargaanJob::dispatch($siasn))
        ])->dispatch();

        return back()->with('toast', ['message' => 'Sinkronisasi sedang diproses.']);
    }
}
