<?php

namespace App\Integration\Siasn\Request\Simpeg;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPnsDataUtamaRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $nip)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/pns/data-utama/' . $this->nip;
    }
}
