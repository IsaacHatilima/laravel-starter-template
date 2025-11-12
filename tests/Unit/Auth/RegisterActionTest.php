<?php

use App\Actions\Auth\RegisterAction;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;

test('that true is true', function () {
    Queue::fake();

    $user = new User(['email' => 'john@mail.com']);

    $userRepo = Mockery::mock(UserRepository::class);
    $profileRepo = Mockery::mock(ProfileRepository::class);

    $userRepo->shouldReceive('create')->once()->andReturn($user);
    $profileRepo->shouldReceive('save')->once();

    $action = new RegisterAction($profileRepo, $userRepo);

    $response = $action->create([
                                    'first_name' => 'John',
                                    'last_name' => 'Doe',
                                    'email' => 'john@mail.com',
                                    'password' => 'Password1#',
                                    'password_confirmation' => 'Password1#',
                                ]);

    Queue::assertPushed(SendVerificationEmailJob::class);

    expect($response)->toBe($user);
});
