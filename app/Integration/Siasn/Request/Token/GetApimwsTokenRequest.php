<?php

namespace App\Integration\Siasn\Request\Token;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class GetApimwsTokenRequest extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    public function __construct(public int $timeout = 60)
    {
        return $this->withBasicAuth(
            config('services.apimws-bkn.username'),
            config('services.apimws-bkn.password')
        );
    }

    protected function defaultConfig(): array
    {
        return [
            'timeout' => $this->timeout
        ];
    }

    public function resolveEndpoint(): string
    {
        return config('services.apimws-bkn.token_url');
    }

    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'client_credentials'
        ];
    }
}
