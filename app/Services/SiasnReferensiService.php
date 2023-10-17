<?php

namespace App\Services;

use App\Integration\Siasn\Connector\SiasnReferensiConnector;
use App\Integration\Siasn\Request\Referensi\GetAgamaRequest;
use App\Integration\Siasn\Request\Referensi\GetAlasanHukDisRequest;
use App\Integration\Siasn\Request\Referensi\GetAsnJenisJabatanRequest;
use App\Integration\Siasn\Request\Referensi\GetKedudukanHukumRequest;
use App\Integration\Siasn\Request\Referensi\GetRefDokumenRequest;
use App\Models\Siasn\Referensi\Agama;
use App\Models\Siasn\Referensi\AlasanHukDis;
use App\Models\Siasn\Referensi\KedudukanHukum;
use App\Models\Siasn\Referensi\RefDokumen;
use Saloon\Contracts\Authenticator;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\PendingRequest;

class SiasnReferensiService
{
    public SiasnReferensiConnector $connector;
    private \Closure $resetToken;

    public function __construct()
    {
        $this->connector = new SiasnReferensiConnector();

        $this->connector->authenticate($this->refreshAccessToken());

        $this->resetToken = function ($exception, PendingRequest $pendingRequest) {
            if (!$exception instanceof RequestException || $exception->getResponse()->status() !== 401) {
                return false;
            }

            $pendingRequest->authenticate($this->refreshAccessToken());

            return true;
        };
    }

    protected function refreshAccessToken(): Authenticator
    {
        return $this->connector->getAccessToken();
    }

    public function fetchAgama(): void
    {
        $response = $this->connector->sendAndRetry(new GetAgamaRequest(), 3, 100, $this->resetToken);

        $results = $response->json('results');

        Agama::upsert($results, ['id']);
    }

    public function fetchAlasanHukDis(): void
    {
        $response = $this->connector->sendAndRetry(new GetAlasanHukDisRequest(), 3, 100, $this->resetToken);

        $result = $response->json('results');

        AlasanHukDis::upsert($result, ['id']);
    }

    public function fetchAsnJenisJabatan(): void
    {
        $response = $this->connector->sendAndRetry(new GetAsnJenisJabatanRequest(), 3, 100, $this->resetToken);

        $result = $response->json('results');
    }

    public function fetchKedudukanHukum(): void
    {
        $response = $this->connector->sendAndRetry(new GetKedudukanHukumRequest(), 3, 100, $this->resetToken);

        $result = $response->json('results');

        KedudukanHukum::upsert($result, ['id']);
    }

    public function fetchRefDokumen(): void
    {
        $response = $this->connector->sendAndRetry(new GetRefDokumenRequest, 3, 1000, $this->resetToken);

        $result = $response->json('results');

        RefDokumen::upsert($result, ['id']);
    }
}
