<?php

namespace App\Socialite;

use SocialiteProviders\Manager\Exception\InvalidArgumentException;
use SocialiteProviders\OIDC\Provider as BaseOidcProvider;

class OidcProvider extends BaseOidcProvider
{
    /**
     * Build an RP-Initiated Logout URL (https://openid.net/specs/openid-connect-rpinitiated-1_0.html).
     * Not provided by the base package.
     */
    public function getLogoutUrl(?string $redirectUri = null, ?string $clientId = null, ?string $idTokenHint = null, ...$additionalParameters): string
    {
        $endSessionEndpoint = $this->getOpenIdConfig()['end_session_endpoint'] ?? null;

        if ($endSessionEndpoint === null) {
            throw new InvalidArgumentException('The OIDC provider does not advertise an end_session_endpoint.');
        }

        if ($redirectUri === null) {
            return $endSessionEndpoint;
        }

        $logoutUrl = $endSessionEndpoint.'?post_logout_redirect_uri='.urlencode($redirectUri);

        if ($clientId !== null) {
            $logoutUrl .= '&client_id='.urlencode($clientId);
        }

        if ($idTokenHint !== null) {
            $logoutUrl .= '&id_token_hint='.urlencode($idTokenHint);
        }

        foreach ($additionalParameters as $parameter) {
            if (! is_array($parameter) || count($parameter) > 1) {
                throw new InvalidArgumentException('Invalid argument. Expected an array with a key and a value.');
            }

            $parameterKey = array_keys($parameter)[0];
            $parameterValue = array_values($parameter)[0];

            $logoutUrl .= "&{$parameterKey}=".urlencode($parameterValue);
        }

        return $logoutUrl;
    }

    /**
     * Exposes the base package's protected, cached JWKS fetch so the
     * back-channel logout handler can verify logout_token signatures
     * without duplicating discovery/caching logic.
     */
    public function getJwksForVerification(): array
    {
        return $this->getJwks();
    }

    public function getIssuer(): string
    {
        return rtrim($this->getConfig('base_url'), '/');
    }
}
