<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\GoogleLinkAction;
use App\Actions\Auth\GoogleLoginAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        $cacheKey = 'google_login_'.request()->ip();
        Cache::put($cacheKey, true, now()->addMinutes(10));

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback after Google authentication.
     */
    public function handleGoogleCallback(GoogleLoginAction $loginAction, GoogleLinkAction $linkAction): RedirectResponse
    {
        $cacheKey = 'google_login_'.request()->ip();
        $isLogin = Cache::pull($cacheKey);

        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        // Get Google user
        try {
            /** @var AbstractUser $googleUser */
            $googleUser = $driver->stateless()->user();
        } catch (Exception $e) {
            return to_route('login')
                ->with('warning', __('Google authentication failed.'));
        }

        // Validate Google user fields
        if (! isset(
            $googleUser->user['given_name'],
            $googleUser->user['family_name']
        )
        ) {
            return to_route('login')
                ->with('warning', __('Google account missing names.'));
        }

        // Login and redirect to dashboard
        if ($isLogin) {
            return $loginAction->execute($googleUser);
        }

        $state = request()->input('state');

        if (! is_string($state)) {
            abort(400, 'Invalid OAuth state parameter.');
        }

        $userId = decrypt(Str::after($state, 'link_'));
        /** @var User $user */
        $user = User::query()->findOrFail($userId);

        // Callback deletes the user from session, so we need to re-authenticate
        Auth::login($user);

        // Link Google account to the authenticated user
        $response = $linkAction->execute($googleUser, $user);

        return to_route('connected-accounts.edit')
            ->with($response['type'], $response['message']);
    }
}
