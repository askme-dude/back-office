<?php

namespace App\Console\Commands;

use App\Jobs\SyncPnsDataUtamaJob;
use App\Models\Siasn\SiasnPnsDataUtama;
use Illuminate\Console\Command;

class SyncDataPegawaiCommand extends Command
{
    protected $signature = 'pegawai:sync-data';

    protected $description = 'Synchronize data pegawai from siasn';

    public function handle(): void
    {
        SiasnPnsDataUtama::all()->each(fn($siasn) => SyncPnsDataUtamaJob::dispatch($siasn));
    }
}
