<?php

namespace App\Integration\Siasn\Request\Simpeg;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPnsDataPasangan extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public string|int $nip)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/pns/data-pasangan/' . $this->nip;
    }
}
