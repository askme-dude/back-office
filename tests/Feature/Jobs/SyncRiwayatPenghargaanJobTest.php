<?php

use App\Jobs\SyncRiwayatPenghargaanJob;
use App\Models\Pegawai;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Siasn\SiasnPnsRwPenghargaan;

test('synchronize data riwayat penghargaan siasn', function () {
    $siasn = SiasnPnsRwPenghargaan::factory()->create();
    Pegawai::factory()->create(['nip' => $siasn->siasnPegawai->nipBaru]);

    SyncRiwayatPenghargaanJob::dispatch($siasn);

    expect(PegawaiRiwayatPenghargaan::first())
        ->bkn_id->toEqual($siasn->id);
});
