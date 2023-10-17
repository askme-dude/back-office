<?php

use App\Jobs\GetSiasnRwPenghargaanJob;
use App\Models\PegawaiRiwayatPenghargaan;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

test('fetch siasn riwayat penghargaan by riwayat id', function () {
    \Saloon\Laravel\Facades\Saloon::fake([
        '*' => function (PendingRequest $request) {
            $reflection = new ReflectionClass($request->getRequest());

            return MockResponse::fixture('Simpeg/' . $reflection->getShortName());
        },
    ]);

    $riwayat = PegawaiRiwayatPenghargaan::factory()->create(['bkn_id' => '094bbc1e-4072-49b1-8fdf-20eafe223fd0']);

    GetSiasnRwPenghargaanJob::dispatch($riwayat);

    expect($riwayat->siasnRwPenghargaan->id)->toEqual('094bbc1e-4072-49b1-8fdf-20eafe223fd0');
});
