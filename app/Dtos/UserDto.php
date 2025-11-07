<?php

namespace App\Dtos;

use App\Contracts\DtoContract;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use InvalidArgumentException;

readonly class UserDto implements DtoContract
{
    public function __construct(
        public string $id,
        public string $email,
        public ?Carbon $email_verified_at,
        public ?Carbon $two_factor_confirmed_at,
        public bool $is_active,
        public ?Carbon $last_login_at,
        public Profile $profile,
    ) {
    }

    /**
     * Convert the DTO to an array representation.
     *
     * @return array{
     *      id: string,
     *      email: string,
     *      email_verified_at: Carbon|null,
     *      two_factor_confirmed_at: Carbon|null,
     *      is_active: bool,
     *      last_login_at: Carbon|null,
     *      profile: array<string, string|int|null>
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'two_factor_confirmed_at' => $this->two_factor_confirmed_at,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at,
            'profile' => ProfileDto::fromModel($this->profile)->toArray(),
        ];
    }

    public static function fromModel(object $model): self
    {
        if (! $model instanceof User) {
            throw new InvalidArgumentException('Expected instance of User');
        }

        return new self(
            id: $model->id,
            email: $model->email,
            email_verified_at: $model->email_verified_at,
            two_factor_confirmed_at: $model->two_factor_confirmed_at,
            is_active: $model->is_active,
            last_login_at: $model->last_login_at,
            profile: $model->profile,
        );
    }
}
