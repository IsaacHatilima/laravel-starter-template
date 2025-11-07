<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Dtos\ProfileDto;
use App\Models\Profile;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateProfileAction
{
    private ProfileRepository $profileRepository;

    private UserRepository $userRepository;

    public function __construct(
        ProfileRepository $profileRepository,
        UserRepository $userRepository
    ) {
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Throwable
     */
    public function execute(Profile $profile, ProfileDto $dto, string $email): Profile
    {
        return DB::transaction(function () use ($profile, $dto, $email) {
            $profile->first_name = $dto->firstName;
            $profile->last_name = $dto->lastName;

            $this->userRepository->updateEmail($profile->user, $email);

            return $this->profileRepository->save($profile);
        });
    }
}
