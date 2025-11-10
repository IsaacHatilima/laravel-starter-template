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
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Throwable;

class RegisterAction implements CreatesNewUsers
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
     * @param array<string, mixed> $input
     * @throws Throwable
     */
    public function create(array $input): User
    {
        $rules = (new RegisterRequest())->rules();
        $validated = Validator::make($input, $rules)->validate();

        $request = new RegisterRequest();
        $request->merge($validated);


        return DB::transaction(function () use ($request): User {
            // Create User instance
            $user = $this->userRepository->create([
                'email' => strtolower($request->string('email')->value()),
                'password' => Hash::make($request->string('password')->value()),
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
