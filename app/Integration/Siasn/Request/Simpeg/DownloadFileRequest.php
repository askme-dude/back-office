<?php

namespace App\Integration\Siasn\Request\Simpeg;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class DownloadFileRequest extends Request
{
    use AlwaysThrowOnErrors;

    protected Method $method = Method::GET;

    public function __construct(public string $filePath)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/download-dok';
    }

    protected function defaultHeaders(): array
    {
        return ['accept' => 'application/octet-stream'];
    }

    protected function defaultConfig(): array
    {
        return ['timeout' => 60];
    }

    protected function defaultQuery(): array
    {
        return ['filePath' => $this->filePath];
    }
}
