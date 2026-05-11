<?php

use App\Models\User;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/login', function() {
    # Store the requested URL in the session
    session()->put('intended_url', url()->previous());

    # Redirect to Keycloak for authentication
    return Socialite::driver('keycloak')
        ->scopes(['profile', 'email'])
        ->redirect();
})->name('login');

Route::get('/auth/callback', function() {
    # Retrieve the requested URL from the session
    $intendedUrl = session('intended_url');

    # Get user information from Keycloak and update or create the user in the database
    $oidcUser = Socialite::driver('keycloak')->stateless()->user();
    
    $user = User::updateOrCreate([
        'username' => $oidcUser->nickname,
    ], [
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

    # Redirect to the requested URL
    return redirect()->intended($intendedUrl);
});

Route::get('/auth/logout', function () {
    $id_token = auth()->user()->oidc_id_token;

    # Log out the user from the application
    Auth::logout();

    # Tell Keycloak to log out the user and redirect to last page visited in the application
    return redirect(Socialite::driver('keycloak')->getLogoutUrl(url()->previous(), env('KEYCLOAK_CLIENT_ID'), $id_token));
})->name('logout');
