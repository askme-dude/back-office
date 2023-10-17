<?php

namespace App\Http\Controllers\Siasn;

use App\Http\Controllers\Controller;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use App\Services\SiasnSimpegService;

class FetchPnsRwPenghargaanController extends Controller
{
    public function __invoke(SiasnPnsRwPenghargaan $penghargaan, SiasnSimpegService $service)
    {
        $service->fetchRwPenghargaan($penghargaan->id);

        return back()->with('toast', ['message' => 'Sinkronisasi telah berhasil']);
    }
}
