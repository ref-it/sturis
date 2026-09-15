<?php

namespace App\Socialite;

use SocialiteProviders\Manager\Exception\InvalidArgumentException;
use SocialiteProviders\Manager\OAuth2\User;
use SocialiteProviders\OIDC\Provider as BaseOidcProvider;

class OidcProvider extends BaseOidcProvider
{
    /**
     * The token endpoint response of the current request.
     *
     * @var array<string, mixed>
     */
    private array $tokenResponse = [];

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        return $this->tokenResponse = parent::getAccessTokenResponse($code);
    }

    /**
     * The base package overrides user() and, unlike the manager it builds on,
     * never puts the token endpoint response back on the user. That drops the
     * raw id_token, which RP-initiated logout needs as its id_token_hint, so
     * attach the response here.
     */
    public function user()
    {
        $user = parent::user();

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->tokenResponse);
        }

        return $user;
    }

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
