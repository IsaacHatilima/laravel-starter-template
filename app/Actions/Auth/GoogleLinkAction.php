<?php

namespace App\Actions\Auth;

use App\Models\ConnectedAccount;
use App\Models\User;
use Laravel\Socialite\AbstractUser;

class GoogleLinkAction
{
    public function __construct()
    {
    }

    /**
     * @return array{type: string, message: string}
     */
    public function execute(
        AbstractUser $googleUser,
        User $authUser
    ): array {
        // Check if an account is already linked
        if (ConnectedAccount::query()
            ->where('identifier', $googleUser->getEmail())
            ->where('service', 'google')
            ->exists()
        ) {
            return [
                'type' => 'warning',
                'message' => 'This Google account is already linked.',
            ];
        }

        session(['linkAuth' => true]);

        $authUser->connectedAccounts()->create([
            'service' => 'google',
            'identifier' => $googleUser->getEmail(),
        ]);

        return [
            'type' => 'success',
            'message' => 'Google account linked successfully.',
        ];
    }
}
