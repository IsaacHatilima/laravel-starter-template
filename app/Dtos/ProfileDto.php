<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Contracts\DtoContract;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Profile;
use Illuminate\Support\Str;
use InvalidArgumentException;

readonly class ProfileDto implements DtoContract
{
    public function __construct(
        public ?string $id,
        public string $firstName,
        public string $lastName,
    ) {
    }

    public static function fromModel(object $model): self
    {
        if (! $model instanceof Profile) {
            throw new InvalidArgumentException('Expected instance of Profile');
        }

        return new self(
            id: $model->id,
            firstName: $model->first_name,
            lastName: $model->last_name,
        );
    }

    public static function fromRegisterRequest(RegisterRequest $request): self
    {
        return new self(
            id: null,
            firstName: trim(Str::title($request->string('first_name')->value())),
            lastName: trim(Str::title($request->string('last_name')->value())),
        );
    }

    public static function fromUpdateRequest(ProfileUpdateRequest $request): self
    {
        return new self(
            id: null,
            firstName: trim(Str::title($request->string('first_name')->value())),
            lastName: trim(Str::title($request->string('last_name')->value())),
        );
    }

    /**
     * Convert the DTO to an array representation.
     *
     * @return array{
     *      id: string|null,
     *      first_name: string,
     *      last_name: string,
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ];
    }
}
