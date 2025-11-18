<?php

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

test('user can login with linked google account', function () {
    $user = createUser();

    $user
        ->connectedAccounts()
        ->create([
             'service' => 'google',
             'user_id' => $user->id,
             'identifier' => $user->email,
        ]);

    Cache::shouldReceive('pull')
        ->with('google_login_' . request()->ip())
        ->andReturn(true);

    $googleUser = new User();
    $googleUser->map([
         'email' => $user->email,
         'user' => [
             'given_name' => $user->profile->first_name,
             'family_name' => $user->profile->last_name,
         ],
    ]);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn(
            Mockery::mock('Laravel\Socialite\Two\GoogleProvider')
                ->shouldReceive('stateless')
                ->once()
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('user')
                ->once()
                ->andReturn($googleUser)
                ->getMock()
        );

    $state = 'link_' . encrypt($user->id);

    $this
        ->followingRedirects()
        ->get(route('google.callback', ['state' => $state]));

    $this->assertAuthenticated();
});

test('user cannot login without linked google account', function () {
    $user = createUser();

    Cache::shouldReceive('pull')
        ->with('google_login_' . request()->ip())
        ->andReturn(true);

    $googleUser = new User();
    $googleUser->map([
         'email' => $user->email,
         'user' => [
             'given_name' => $user->profile->first_name,
             'family_name' => $user->profile->last_name,
         ],
    ]);

    Socialite::shouldReceive('driver')
        ->with('google')
        ->once()
        ->andReturn(
            Mockery::mock('Laravel\Socialite\Two\GoogleProvider')
                ->shouldReceive('stateless')
                ->once()
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('user')
                ->once()
                ->andReturn($googleUser)
                ->getMock()
        );

    $state = 'link_' . encrypt($user->id);

    $this
        ->followingRedirects()
        ->get(route('google.callback', ['state' => $state]));

    $this->assertGuest();
});
