<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OidcBackchannelSession;
use App\Socialite\OidcProvider;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

/**
 * Receives OIDC Back-Channel Logout notifications
 * (https://openid.net/specs/openid-connect-backchannel-1_0.html).
 */
class BackchannelLogoutController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $logoutToken = $request->input('logout_token');

        if (! is_string($logoutToken) || $logoutToken === '') {
            return $this->invalidRequest('Missing logout_token.');
        }

        /** @var OidcProvider $provider */
        $provider = Socialite::driver('oidc');

        try {
            $keySet = JWK::parseKeySet($provider->getJwksForVerification());
            $payload = (array) json_decode(json_encode(JWT::decode($logoutToken, $keySet)), true);
        } catch (Exception $e) {
            return $this->invalidRequest('Unable to verify logout_token: '.$e->getMessage());
        }

        $error = $this->validateClaims($payload, $provider);

        if ($error !== null) {
            return $this->invalidRequest($error);
        }

        if (isset($payload['jti']) && ! Cache::add('oidc_backchannel_logout_jti_'.$payload['jti'], true, 300)) {
            return $this->invalidRequest('logout_token has already been used.');
        }

        $this->terminateSessions($payload['sub'] ?? null, $payload['sid'] ?? null);

        return response('', 200)->header('Cache-Control', 'no-store');
    }

    private function validateClaims(array $payload, OidcProvider $provider): ?string
    {
        if (($payload['iss'] ?? null) !== $provider->getIssuer()) {
            return 'logout_token has an unexpected issuer.';
        }

        $audience = (array) ($payload['aud'] ?? []);

        if (! in_array(config('services.oidc.client_id'), $audience, true)) {
            return 'logout_token has an unexpected audience.';
        }

        if (! isset($payload['iat']) || abs(time() - (int) $payload['iat']) > 300) {
            return 'logout_token has an invalid or stale iat.';
        }

        if (! isset($payload['events']['http://schemas.openid.net/event/backchannel-logout'])) {
            return 'logout_token is missing the backchannel-logout event.';
        }

        if (isset($payload['nonce'])) {
            return 'logout_token must not contain a nonce.';
        }

        if (! isset($payload['sub']) && ! isset($payload['sid'])) {
            return 'logout_token must contain a sub or sid.';
        }

        return null;
    }

    private function terminateSessions(?string $sub, ?string $sid): void
    {
        $query = OidcBackchannelSession::query();

        if ($sid !== null) {
            $query->where('sid', $sid);
        } else {
            $query->where('sub', $sub);
        }

        $sessions = $query->get();

        if ($sessions->isEmpty()) {
            return;
        }

        DB::table(config('session.table', 'sessions'))
            ->whereIn('id', $sessions->pluck('laravel_session_id'))
            ->delete();

        OidcBackchannelSession::whereIn('id', $sessions->pluck('id'))->delete();
    }

    private function invalidRequest(string $description): Response
    {
        return response()->json([
            'error' => 'invalid_request',
            'error_description' => $description,
        ], 400)->header('Cache-Control', 'no-store');
    }
}
