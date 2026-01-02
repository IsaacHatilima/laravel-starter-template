<?php

use App\Actions\Profile\UpdateProfileAction;
use App\DTOs\Command\Settings\ProfileUpdateRequestDTO;
use App\Models\User;

test('profile update action class', function () {
    /** @var User $createdUser */
    $createdUser = createUser();

    $action = app(UpdateProfileAction::class);
    $dto = new ProfileUpdateRequestDTO('john@mail.com', 'John', 'Doe');

    $user = $action->execute($dto, $createdUser);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->email->toBe('john@mail.com');

    $this->assertDatabaseHas('users', [
        'email' => 'john@mail.com',
    ]);

    $this->assertDatabaseHas('profiles', [
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
});
