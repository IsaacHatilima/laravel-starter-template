<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\DTOs\Command\Settings\ProfileUpdateRequestDTO;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateProfileAction
{
    /**
     * @throws Throwable
     */
    public function execute(ProfileUpdateRequestDTO $dto, User $user): User
    {
        return DB::transaction(function () use ($dto, $user) {
            $user->profile->update([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
            ]);

            if ($user->email !== $dto->email) {
                $user->email = $dto->email;
                $user->email_verified_at = null;
                $user->save();

                SendVerificationEmailJob::dispatch($user)->afterCommit();
            }

            return $user->load('profile');
        });
    }
}
