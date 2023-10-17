<?php

use App\Integration\Siasn\Request\Simpeg\PostPenghargaanRequest;
use App\Jobs\PostSiasnPenghargaanJob;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Penghargaan;
use App\Models\Siasn\Referensi\RefDokumen;
use App\Models\Siasn\SiasnPnsDataUtama;
use Illuminate\Http\UploadedFile;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

test('post siasn penghargaan job', function () {
    \Illuminate\Support\Facades\Http::fake([
        '*/upload-dok' => Http::response([
            'code' => 1,
            'data' => [
                'dok_id' => '891',
                'dok_nama' => 'Dok Realisasi SKP',
                'dok_uri' => 'peremajaan/usulan/7E2C0FACBDF1B6DDE050640A3C0313D4_20231015_044007_test.pdf',
                'slug' => '891',
                'object' => 'peremajaan/usulan/7E2C0FACBDF1B6DDE050640A3C0313D4_20231015_044007_test.pdf',
            ],
            'message' => 'File berhasil di upload'
        ])
    ]);

    \Saloon\Laravel\Facades\Saloon::fake([
        '*' => function (PendingRequest $request) {
            $reflection = new ReflectionClass($request->getRequest());

            return MockResponse::fixture('Simpeg/' . $reflection->getShortName());
        },
    ]);

    $riwayat = PegawaiRiwayatPenghargaan::factory()
        ->for(Penghargaan::factory()->state(['id' => 201]))
        ->create();
    RefDokumen::factory()->create(['detailLayananNama' => 'Riwayat Penghargaan']);

    $uploaded = UploadedFile::fake()->create('example.pdf', 5, 'application/pdf');
    $riwayat->addMedia($uploaded)->toMediaCollection('media_sk');
    SiasnPnsDataUtama::factory()->create(['nipBaru' => $riwayat->pegawai->nip]);

    PostSiasnPenghargaanJob::dispatch($riwayat);

    $baseUrl = config('services.apimws-bkn.base_url');

    Http::assertSent(fn(\Illuminate\Http\Client\Request $request) => $request
            ->url() === $baseUrl . '/upload-dok');

    \Saloon\Laravel\Facades\Saloon::assertSent(PostPenghargaanRequest::class);
});
