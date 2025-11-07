<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Profile;

class ProfileRepository
{
    public function __construct()
    {
    }

    /**
     * Saves or updates a profile.
     */
    public function save(Profile $profile): Profile
    {
        $profile->save();

        return $profile->refresh();
    }
}
