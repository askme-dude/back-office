<?php

use App\Integration\Siasn\Request\Referensi\GetAgamaRequest;
use App\Integration\Siasn\Request\Referensi\GetAlasanHukDisRequest;
use App\Integration\Siasn\Request\Referensi\GetAsnJenisJabatanRequest;
use App\Integration\Siasn\Request\Referensi\GetKedudukanHukumRequest;
use App\Integration\Siasn\Request\Referensi\GetRefDokumenRequest;
use App\Models\Siasn\Referensi\Agama;
use App\Models\Siasn\Referensi\AlasanHukDis;
use App\Models\Siasn\Referensi\KedudukanHukum;
use App\Models\Siasn\Referensi\RefDokumen;
use App\Services\SiasnReferensiService;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

describe('siasn referensi data', function () {

    beforeEach(function () {
        \Saloon\Laravel\Facades\Saloon::fake([
            '*' => function (PendingRequest $request) {
                $reflection = new ReflectionClass($request->getRequest());

                return MockResponse::fixture('Referensi/' . $reflection->getShortName());
            },
        ]);
    });

    it('can fetch agama and store to database', function () {
        $service = new SiasnReferensiService();

        $service->fetchAgama();

        \Saloon\Laravel\Facades\Saloon::assertSent(GetAgamaRequest::class);

        expect(Agama::count())->not->toBeEmpty();
    });

    it('can fetch alasan hukuman disiplin and store to database', function () {
        $service = new SiasnReferensiService();

        $service->fetchAlasanHukDis();

        \Saloon\Laravel\Facades\Saloon::assertSent(GetAlasanHukDisRequest::class);

        expect(AlasanHukDis::count())->not->toBeEmpty();
    });

    it('can fetch asn jenis jabatan', function () {
        $service = new SiasnReferensiService();

        $service->fetchAsnJenisJabatan();

        \Saloon\Laravel\Facades\Saloon::assertSent(GetAsnJenisJabatanRequest::class);
    });

    it('can fetch kedudukan hukum and store to database', function () {
        $service = new SiasnReferensiService();

        $service->fetchKedudukanHukum();

        \Saloon\Laravel\Facades\Saloon::assertSent(GetKedudukanHukumRequest::class);

        expect(KedudukanHukum::count())->not->toBeEmpty();
    });

    it('can fetch ref dokumen and store to database', function () {
        $service = new SiasnReferensiService();

        $service->fetchRefDokumen();

        Saloon::assertSent(GetRefDokumenRequest::class);

        expect(RefDokumen::count())->not->toBeEmpty();
    });
});
