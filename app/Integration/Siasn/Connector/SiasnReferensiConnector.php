<?php

namespace App\Integration\Siasn\Connector;

use App\Integration\Siasn\Request\Token\GetApimwsTokenRequest;
use Saloon\Contracts\Request;
use Saloon\Contracts\Sender;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Http\Senders\GuzzleSender;
use Saloon\Traits\OAuth2\ClientCredentialsGrant;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class SiasnReferensiConnector extends Connector
{
    use AlwaysThrowOnErrors, ClientCredentialsGrant;

    public function resolveBaseUrl(): string
    {
        return config('services.apimws-bkn.reference_url');
    }

    protected function defaultSender(): Sender
    {
        return resolve(GuzzleSender::class);
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId('client_id')
            ->setClientSecret('my-client-secret')
            ->setDefaultScopes(['default']);
    }

    protected function resolveAccessTokenRequest(OAuthConfig $oauthConfig, array $scopes = [], string $scopeSeparator = ' '): Request
    {
        return new GetApimwsTokenRequest();
    }
}
