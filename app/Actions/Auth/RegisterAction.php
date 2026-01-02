<?php

namespace App\Actions\Auth;

use App\DTOs\Command\Auth\RegisterRequestDTO;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Throwable;

readonly class RegisterAction implements CreatesNewUsers
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws Throwable
     */
    public function create(array $input): User
    {
        $rules = (new RegisterRequest())->rules();
        $validated = Validator::make($input, $rules)->validate();

        $request = new RegisterRequest();
        $request->merge($validated);
        $dto = RegisterRequestDTO::fromRequest($request);

        return DB::transaction(function () use ($dto): User {
            $user = $this->userRepository->create([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ]);

            $user->profile()->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
            ]);

            SendVerificationEmailJob::dispatch($user);

            return $user;
        });
    }
}
