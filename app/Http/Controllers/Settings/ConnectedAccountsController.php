<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class ConnectedAccountsController extends Controller
{
    use AuthorizesRequests;

    public function edit(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('settings/connected-accounts', [
            'connectedAccounts' => $user->connectedAccounts,
            'linkAuth' => session()->pull('linkAuth'),
        ]);
    }

    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        /** @var User $user */
        $user = request()->user();
        $userId = encrypt($user->id);

        $cacheKey = 'google_login_'.request()->ip();
        Cache::put($cacheKey, false, now()->addMinutes(10));

        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->with([
            'state' => 'link_'.$userId,
        ])->redirect();
    }

    /**
     * Delete connection
     */
    public function destroy(ConnectedAccount $connectedAccount): RedirectResponse
    {
        $this->authorize('delete', $connectedAccount);

        $connectedAccount->delete();

        return back()->with('success', 'Connected account deleted successfully');
    }
}
