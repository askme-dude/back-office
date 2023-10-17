<?php

namespace App\Jobs;

use App\Models\PegawaiRiwayatPenghargaan;
use App\Services\SiasnSimpegService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSiasnRwPenghargaanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public PegawaiRiwayatPenghargaan $riwayat)
    {
    }

    public function handle(): void
    {
        $service = new SiasnSimpegService();

        $service->fetchRwPenghargaan($this->riwayat->fresh()->bkn_id);
    }
}
