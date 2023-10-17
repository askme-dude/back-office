<?php

namespace App\Integration\Siasn\Authenticator;

use App\Integration\Siasn\Request\Token\GetApimwsTokenRequest;
use App\Integration\Siasn\Request\Token\GetSiasnTokenRequest;
use Saloon\Contracts\Authenticator;
use Saloon\Contracts\PendingRequest;

class SiasnSimpegAuthenticator implements Authenticator
{
    public function set(PendingRequest $pendingRequest): void
    {
        if ($pendingRequest->getRequest() instanceof GetSiasnTokenRequest || $pendingRequest->getRequest() instanceof GetApimwsTokenRequest) {
            return;
        }

        $siasnToken = $pendingRequest->getConnector()->send(new GetSiasnTokenRequest());
        $apimws = $pendingRequest->getConnector()->send(new GetApimwsTokenRequest());

        $pendingRequest->headers()
            ->add('Authorization', 'Bearer ' . $apimws->json('access_token'))
            ->add('Auth', 'Bearer ' . $siasnToken->json('access_token'));
    }
}
