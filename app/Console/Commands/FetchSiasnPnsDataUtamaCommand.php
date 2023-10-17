<?php

namespace App\Console\Commands;

use App\Services\SiasnSimpegService;
use Illuminate\Console\Command;

class FetchSiasnPnsDataUtamaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'siasn:fetch-pns-data-utama';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get PNS Data Utama from SIASN API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        (new SiasnSimpegService())->fetchAllPnsDataUtama();
    }
}
