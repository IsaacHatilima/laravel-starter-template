<?php

use App\Actions\Profile\UpdateProfileAction;
use App\Dtos\ProfileDto;
use App\Models\Profile;

test('profile update action class', function () {
    $user = createUser();

    $dto = new ProfileDto(
        id: $user->profile->id,
        firstName: 'John',
        lastName: 'Doe',
    );

    $action = app(UpdateProfileAction::class);

    $result = $action->execute($user->profile, $dto, $user->email);

    expect($result)->toBeInstanceOf(Profile::class);
});
