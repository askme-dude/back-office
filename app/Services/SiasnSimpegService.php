<?php

namespace App\Services;

use App\Data\SiasnPenghargaanData;
use App\Data\SiasnPnsDataOrtuData;
use App\Data\SiasnPnsDataPasanganData;
use App\Data\SiasnUploadedFile;
use App\Integration\Siasn\Authenticator\SiasnSimpegAuthenticator;
use App\Integration\Siasn\Connector\SiasnSimpegConnector;
use App\Integration\Siasn\Request\Simpeg\DownloadFileRequest;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataOrtuRequest;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataPasanganRequest;
use App\Integration\Siasn\Request\Simpeg\GetPnsDataUtamaRequest;
use App\Integration\Siasn\Request\Simpeg\GetPnsRwPenghargaanRequest;
use App\Integration\Siasn\Request\Simpeg\GetRwPenghargaanRequest;
use App\Integration\Siasn\Request\Simpeg\PostPenghargaanRequest;
use App\Integration\Siasn\Request\Token\GetApimwsTokenRequest;
use App\Integration\Siasn\Request\Token\GetSiasnTokenRequest;
use App\Models\Siasn\SiasnPnsDataOrtu;
use App\Models\Siasn\SiasnPnsDataPasangan;
use App\Models\Siasn\SiasnPnsDataUtama;
use App\Models\Siasn\SiasnPnsRwPenghargaan;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use ReflectionException;
use Saloon\Exceptions\InvalidResponseClassException;
use Saloon\Exceptions\PendingRequestException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

class SiasnSimpegService
{
    public SiasnSimpegConnector $connector;
    private \Closure $resetToken;

    public function __construct()
    {
        $this->connector = new SiasnSimpegConnector();

        $this->connector->authenticate(new SiasnSimpegAuthenticator);

        $this->resetToken = function ($exception, $pendingRequest) {
            if (!$exception instanceof RequestException || $exception->getResponse()->status() !== 401) {
                return false;
            }

            $pendingRequest->authenticate(new SiasnSimpegAuthenticator);

            return true;
        };
    }

    /**
     * @throws InvalidResponseClassException
     * @throws ReflectionException
     * @throws FatalRequestException
     * @throws PendingRequestException
     * @throws RequestException
     */
    public function fetchPnsDataUtama(string|int $nip): SiasnPnsDataUtama
    {
        $response = $this->connector->sendAndRetry(new GetPnsDataUtamaRequest($nip), 3, 5000, $this->resetToken);

        $data = $response->json()['data'];

        return SiasnPnsDataUtama::updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    public function fetchAllPnsDataUtama(): void
    {
        foreach (SiapService::getAllNip() as $nip) {
            try {
                $this->fetchPnsDataUtama($nip);
            } catch (NotFoundException $exception) {
                continue;
            }
        }
    }

    public function fetchPnsDataPasangan(string|int $nip): void
    {
        $response = $this->connector->sendAndRetry(new GetPnsDataPasanganRequest($nip), 3, 5000, $this->resetToken);

        $data = $response->json()['data'];

        if ((bool)Arr::get($data, 'listPasangan')) {
            foreach ($data['listPasangan'] as $pasangan) {
                $object = SiasnPnsDataPasanganData::fromResponse($data['idPns'], $pasangan);

                SiasnPnsDataPasangan::updateOrCreate(
                    ['idPns' => $object->idPns, 'orangId' => $object->orangId],
                    $object->toArray()
                );
            }
        }
    }

    public function fetchAllPnsDataPasangan(): void
    {
        foreach (SiapService::getAllNip() as $nip) {
            try {
                $this->fetchPnsDataPasangan($nip);
            } catch (NotFoundException $exception) {
                continue;
            }
        }
    }

    public function fetchRwPenghargaanByNip(string|int $nip): void
    {
        $response = $this->connector->sendAndRetry(new GetPnsRwPenghargaanRequest($nip), 3, 5000, $this->resetToken);

        $data = $response->json()['data'];

        if ($data === 'Data tidak ditemukan') {
            return;
        }

        foreach ($data as $row) {
            SiasnPnsRwPenghargaan::updateOrCreate(['ID' => $row['ID']], $row);
        }
    }

    public function fetchAllRwPenghargaan(): void
    {
        foreach (SiapService::getAllNip() as $nip) {
            try {
                $this->fetchRwPenghargaanByNip($nip);
            } catch (NotFoundException $exception) {
                continue;
            }
        }
    }

    public function fetchPnsDataOrtu(string|int $nip): void
    {
        $response = $this->connector->sendAndRetry(new GetPnsDataOrtuRequest($nip), 3, 5000, $this->resetToken);

        $object = SiasnPnsDataOrtuData::fromResponse($response->json('data'));

        SiasnPnsDataOrtu::updateOrCreate(
            ['idPns' => $object->idPns, 'ayahId' => $object->ayahId, 'ibuId' => $object->ibuId],
            $object->toArray()
        );
    }

    public function fetchAllPnsDataOrtu(): void
    {
        foreach (SiapService::getAllNip() as $nip) {
            try {
                $this->fetchPnsDataOrtu($nip);
            } catch (ClientException $exception) {
                continue;
            }
        }
    }

    public function fetchRwPenghargaan(string $rwPenghargaanId)
    {
        $response = $this->connector->sendAndRetry(new GetRwPenghargaanRequest($rwPenghargaanId), 3, 1000, $this->resetToken);

        if ($response->json('code')) {
            $result = $response->json('data');
            $path = Arr::first($result['path']);
            $data = SiasnPenghargaanData::from([
                ...$result,
                'id' => $result['ID'],
                'path' => [SiasnUploadedFile::from($path)]
            ]);

            return SiasnPnsRwPenghargaan::updateOrCreate(['id' => $data->id], $data->toArray());
        }

        return $response->json();
    }

    /**
     * @throws InvalidResponseClassException
     * @throws ClientException
     * @throws ReflectionException
     * @throws FatalRequestException
     * @throws PendingRequestException
     * @throws RequestException
     */
    public function postPenghargaan(SiasnPenghargaanData $data): string
    {
        $response = $this->connector->sendAndRetry(new PostPenghargaanRequest($data), 3, 1000, $this->resetToken);

        if (!$response->json('success')) {
            throw new ClientException($response, $response->json('message'));
        }

        return $response->json('mapData')['rwPenghargaanId'];
    }

    /**
     * @throws InvalidResponseClassException
     * @throws ReflectionException
     * @throws HttpClientException
     * @throws PendingRequestException
     */
    public function uploadFile(string $filePath, int|string $id_ref_dokumen): SiasnUploadedFile
    {
        $url = config('services.apimws-bkn.base_url') . '/upload-dok';

        $stream = fopen($filePath, 'r');

        $siasnToken = $this->connector->send(new GetSiasnTokenRequest());
        $apimws = $this->connector->send(new GetApimwsTokenRequest());

        $response = Http::withHeaders([
            'Auth' => 'bearer ' . $siasnToken->json('access_token'),
            'Authorization' => 'Bearer ' . $apimws->json('access_token'),
        ])
            ->attach('file', $stream)
            ->acceptJson()
            ->post($url, [
                'id_ref_dokumen' => $id_ref_dokumen,
            ])
            ->throwIf(fn(Response $response) => $response->json('code') != 1);

        return SiasnUploadedFile::from($response->json('data'));
    }

    /**
     * @throws InvalidResponseClassException
     * @throws FatalRequestException
     * @throws ReflectionException
     * @throws RequestException
     * @throws PendingRequestException
     */
    public function downloadFile(string $filePath): \Saloon\Contracts\Response
    {
        return $this->connector->sendAndRetry(new DownloadFileRequest($filePath), 3, 1000, $this->resetToken);
    }
}
