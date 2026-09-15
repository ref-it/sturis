<?php

use App\Http\Controllers\Auth\BackchannelLogoutController;
use App\Models\OidcBackchannelSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/login', function () {
    # Store the requested URL in the session
    session()->put('intended_url', url()->previous());

    # Redirect to the OIDC provider for authentication
    return Socialite::driver('oidc')
        ->scopes(['profile', 'email'])
        ->redirect();
})->name('login');

Route::get('/auth/callback', function () {
    # Retrieve the requested URL from the session
    $intendedUrl = session('intended_url');

    # Get user information from the OIDC provider and update or create the user in the database
    $oidcUser = Socialite::driver('oidc')->stateless()->user();

    $user = User::updateOrCreate([
        'oidc_sub' => $oidcUser->id,
    ], [
        'username' => $oidcUser->user['preferred_username'] ?? $oidcUser->user['nickname'] ?? null,
        'name' => $oidcUser->name,
        'firstname' => $oidcUser->user['given_name'],
        'lastname' => $oidcUser->user['family_name'],
        'email' => $oidcUser->email,
        'groups' => json_encode($oidcUser->user['groups'] ?? []),
        'oidc_token' => $oidcUser->token,
        'oidc_refresh_token' => $oidcUser->refreshToken,
        'oidc_id_token' => $oidcUser->accessTokenResponseBody['id_token'],
    ]);

    # Log the user in
    Auth::login($user);

    # Record which session belongs to this sub/sid so a back-channel logout
    # from the OIDC provider can find and terminate the right session later.
    OidcBackchannelSession::updateOrCreate([
        'sub' => $oidcUser->id,
        'sid' => $oidcUser->user['sid'] ?? null,
    ], [
        'laravel_session_id' => session()->getId(),
    ]);

    # Redirect to the requested URL
    return redirect()->intended($intendedUrl);
});

Route::get('/auth/logout', function () {
    $id_token = auth()->user()->oidc_id_token;

    # Log out the user from the application
    Auth::logout();

    # Tell the OIDC provider to log out the user and redirect back to the app root.
    # This must be a fixed URL (not the dynamic "previous page") so it can be
    # registered as an allowed post-logout redirect URI with the OIDC provider.
    return redirect(Socialite::driver('oidc')->getLogoutUrl(url('/'), config('services.oidc.client_id'), $id_token));
})->name('logout');

Route::post('/auth/backchannel-logout', BackchannelLogoutController::class);
