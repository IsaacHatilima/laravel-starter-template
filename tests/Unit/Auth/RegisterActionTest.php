<?php

use App\Actions\Auth\RegisterAction;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('registers a user and dispatches verification email', function () {
    Queue::fake();

    $action = app(RegisterAction::class);

    $user = $action->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@mail.com',
        'password' => 'Password1#',
        'password_confirmation' => 'Password1#',
    ]);

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

    Queue::assertPushed(SendVerificationEmailJob::class);
});
