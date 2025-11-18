<?php

namespace App\Actions\Auth;

use App\Models\ConnectedAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\AbstractUser;

class GoogleLoginAction
{
    public function __construct()
    {
    }

    public function execute(AbstractUser $googleUser): RedirectResponse
    {
        $connection = ConnectedAccount::query()
            ->where('identifier', $googleUser->getEmail())
            ->where('service', 'google')
            ->first();

        if (! $connection) {
            return to_route('login')
                ->with(
                    'warning',
                    __('No account linked with this Google email.')
                );
        }

        $user = $connection->user()->firstOrFail();

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return to_route('dashboard');
    }
}
