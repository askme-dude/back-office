<?php

namespace App\Integration\Siasn\Request;

use App\Integration\Siasn\Response\ApimwsToken;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;
use Saloon\Traits\Body\HasFormBody;

class CreateApimwsToken extends SoloRequest implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;
    public function __construct()
    {
        return $this->withBasicAuth(
            config('services.apimws-bkn.username'),
            config('services.apimws-bkn.password')
        );
    }

    public function resolveEndpoint(): string
    {
        return 'https://apimws.bkn.go.id/oauth2/token';
    }

    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'client_credentials'
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return ApimwsToken::fromResponse($response);
    }

}