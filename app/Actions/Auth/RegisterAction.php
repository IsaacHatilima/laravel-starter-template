<?php

namespace App\Actions\Auth;

use App\Dtos\ProfileDto;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendVerificationEmailJob;
use App\Models\Profile;
use App\Models\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterAction
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
    public function execute(RegisterRequest $request): User
    {
        return DB::transaction(function () use ($request): User {
            // Create User instance
            $user = $this->userRepository->create([
                'email' => strtolower($request->string('email')->value()),
                'password' => Hash::make($request->string('password')->value()),
                'downloaded_codes' => false,
                'is_active' => true,
            ]);

            // Create Users profile
            $dto = ProfileDto::fromRegisterRequest($request);
            $profile = new Profile();
            $profile->first_name = $dto->firstName;
            $profile->last_name = $dto->lastName;
            $profile->user_id = $user->id;

            $this->profileRepository->save($profile);

            SendVerificationEmailJob::dispatch($user);

            return $user;
        });
    }
}
