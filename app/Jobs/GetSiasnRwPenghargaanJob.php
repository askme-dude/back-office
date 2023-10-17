<?php

namespace App\Jobs;

use App\Services\SiasnSimpegService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetSiasnRwPenghargaanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $siasnRwPenghargaanId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new SiasnSimpegService();

        $service->fetchRwPenghargaan($this->siasnRwPenghargaanId);
    }
}
