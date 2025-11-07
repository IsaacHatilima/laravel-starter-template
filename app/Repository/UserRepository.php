<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    public function __construct()
    {
    }

    /**
     * @param  array<string, string|bool>  $data
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * @param  User  $user
     * @param  string  $email
     */
    public function updateEmail(User $user, string $email): void
    {
        $normalized = strtolower($email);

        if ($user->email !== $normalized) {
            $user->email = $normalized;
            $user->email_verified_at = null;
            $user->save();
        }
    }
}
