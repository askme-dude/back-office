<?php

namespace App\Integration\Siasn\Request\Referensi;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetKedudukanHukumRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/kedudukan-hukum';
    }
}
