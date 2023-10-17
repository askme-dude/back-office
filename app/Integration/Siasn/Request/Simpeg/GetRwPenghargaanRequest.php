<?php

namespace App\Integration\Siasn\Request\Simpeg;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetRwPenghargaanRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(public string $rwPenghargaanId)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/penghargaan/id/' . $this->rwPenghargaanId;
    }
}
