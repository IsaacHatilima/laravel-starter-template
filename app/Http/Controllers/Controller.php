<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    public function currentUser(): User
    {
        $user = auth()->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        return $user;
    }
}
