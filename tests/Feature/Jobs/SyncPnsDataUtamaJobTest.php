<?php

use App\Jobs\SyncPnsDataUtamaJob;
use App\Models\Pegawai;
use App\Models\Siasn\SiasnPnsDataUtama;
use Database\Seeders\AgamaSeeder;
use Database\Seeders\JenisKawinSeeder;
use Database\Seeders\JenisPegawaiSeeder;
use Database\Seeders\StatusPegawaiSeeder;

test('sync pns data utama from siasn', function () {
    $this->seed([
        JenisKawinSeeder::class,
        StatusPegawaiSeeder::class,
        JenisPegawaiSeeder::class,
        AgamaSeeder::class,
    ]);
    
    $siasnPns = SiasnPnsDataUtama::factory()->create();

    SyncPnsDataUtamaJob::dispatch($siasnPns);

    expect(Pegawai::first())
        ->nip->toEqual($siasnPns->nipBaru)
        ->nik->toEqual($siasnPns->nik);
});
