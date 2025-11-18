<?php

namespace App\Policies;

use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConnectedAccountPolicy
{
    use HandlesAuthorization;

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->is_active;
    }

    public function update(User $user, ConnectedAccount $connectedAccount): bool
    {
        return $user->id === $connectedAccount->user_id;
    }

    public function delete(User $user, ConnectedAccount $connectedAccount): bool
    {
        return $user->id === $connectedAccount->user_id;
    }
}
