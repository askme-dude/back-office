<?php

namespace App\Integration\Siasn\Request\Simpeg;

use App\Data\SiasnPenghargaanData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class PostPenghargaanRequest extends Request implements HasBody
{
    use HasJsonBody, AlwaysThrowOnErrors;

    protected Method $method = Method::POST;

    public function __construct(public SiasnPenghargaanData $data)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/penghargaan/save';
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }
}
