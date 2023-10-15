<?php

use App\Data\SiasnPenghargaanData;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataOrtu;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataPasangan;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataUtama;
use App\Integration\Siasn\Request\Simpeg\GetPnsRwPenghargaan;
use App\Integration\Siasn\Request\Simpeg\PostPenghargaan;
use App\Models\PegawaiRiwayatPenghargaan;
use App\Models\Siasn\SiasnPnsDataOrtu;
use App\Models\Siasn\SiasnPnsDataPasangan;
use App\Models\Siasn\SiasnPnsDataUtama;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use App\Services\SiasnSimpegService;
use Illuminate\Http\UploadedFile;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

describe('siasn simpeg data transaction', function () {
    beforeEach(function () {
        \Saloon\Laravel\Facades\Saloon::fake([
            '*' => function (PendingRequest $request) {
                $reflection = new ReflectionClass($request->getRequest());

                return MockResponse::fixture('Simpeg/' . $reflection->getShortName());
            },
        ]);
    });

    it('can fetch siasn pns data utama', function () {
        $service = new SiasnSimpegService();

        $service->fetchPnsDataUtama(199111182019031005);

        \Saloon\Laravel\Facades\Saloon::assertSent(GetPnsDataUtama::class);

        expect(SiasnPnsDataUtama::first()->nipBaru)->toEqual(199111182019031005);
    });

    it('can fetch siasn pns data pasangan', function () {
        $service = new SiasnSimpegService();

        $service->fetchPnsDataPasangan(199111182019031005);

        \Saloon\Laravel\Facades\Saloon::assertSent(GetPnsDataPasangan::class);

        expect(SiasnPnsDataPasangan::count())->not->toBeEmpty();
    });

    it('can fetch siasn pns data ortu', function () {
        $service = new SiasnSimpegService();

        $service->fetchPnsDataOrtu(198308212011011011);

        \Saloon\Laravel\Facades\Saloon::assertSent(GetPnsDataOrtu::class);

        expect(SiasnPnsDataOrtu::count())->not->toBeEmpty();
    });
});

describe('siasn riwayat penghargaan', function () {
    beforeEach(function () {
        Storage::fake();

        \Saloon\Laravel\Facades\Saloon::fake([
            '*' => function (PendingRequest $request) {
                $reflection = new ReflectionClass($request->getRequest());

                return MockResponse::fixture('Simpeg/' . $reflection->getShortName());
            },
        ]);

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

        $uploaded = UploadedFile::fake()->image('example.pdf')->size(5);
        $riwayat = PegawaiRiwayatPenghargaan::factory()->create();
        $this->media = $riwayat->addMedia($uploaded)->toMediaCollection('*');
    });

    it('can fetch siasn riwayat penghargaan', function () {
        $service = new SiasnSimpegService();

        expect(SiasnPnsRwPenghargaan::count())->toBeEmpty();

        $service->fetchPnsRwPenghargaan(197809072002121002);

        \Saloon\Laravel\Facades\Saloon::assertSent(GetPnsRwPenghargaan::class);

        expect(SiasnPnsRwPenghargaan::count())->not->toBeEmpty();
    });

    it('can post siasn riwayat penghargaan', function () {
        $service = new SiasnSimpegService();

        $path = $service->uploadFile($this->media->getPath(), 892);

        $data = new SiasnPenghargaanData(
            hargaId: 201,
            pnsOrangId: 'A8ACA7EBFFF13912E040640A040269BB',
            skDate: fake()->date('d-m-Y'),
            skNomor: fake()->numerify('#######'),
            tahun: 2023,
            path: [$path]
        );

        $service->postPenghargaan($data);

        \Saloon\Laravel\Facades\Saloon::assertSent(PostPenghargaan::class);
    });

    it('can upload file', function () {
        $service = new SiasnSimpegService();

        $baseUrl = config('services.apimws-bkn.base_url');

        $response = $service->uploadFile($this->media->getPath(), 891);

        Http::assertSent(fn(\Illuminate\Http\Client\Request $request) => $request
                ->url() === $baseUrl . '/upload-dok');

        expect($response)->toBeInstanceOf(\App\Data\SiasnUploadedFile::class);
    });
});
