<?php

namespace App\Jobs;

use App\Services\SiasnSimpegService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetSiasnPnsDataUtamaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string|int $nip)
    {
    }

    public function handle(): void
    {
        (new SiasnSimpegService)->fetchPnsDataUtama($this->nip);
    }
}
