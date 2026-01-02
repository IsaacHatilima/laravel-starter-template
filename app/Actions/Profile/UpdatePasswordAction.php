<?php

namespace App\Actions\Profile;

use App\DTOs\Command\Settings\ChangePasswordRequestDTO;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;

final readonly class UpdatePasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(ChangePasswordRequestDTO $dto, User $user): void
    {
        $this->userRepository->update($user, [
            'password' => Hash::make($dto->password),
        ]);
    }
}
