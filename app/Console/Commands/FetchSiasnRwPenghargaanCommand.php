<?php

namespace App\Console\Commands;

use App\Services\SiasnSimpegService;
use Illuminate\Console\Command;

class FetchSiasnRwPenghargaanCommand extends Command
{
    protected $signature = 'siasn:fetch-rw-penghargaan';

    protected $description = 'Get data riwayat penghargaan pegawai from SIASN API';

    public function handle(): void
    {
        (new SiasnSimpegService())->fetchAllRwPenghargaan();
    }
}
