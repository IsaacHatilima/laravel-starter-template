<?php

use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

it('user can add Google auth account', function () {
    $user = createUser();

    $this->actingAs($user)->get(route('connected-accounts.edit'));

    Cache::shouldReceive('pull')
        ->with('google_login_' . request()->ip())
        ->andReturn(false);

    $googleUser = new User();
    $googleUser->map([
                         'email' => 'user@example.com',
                         'user' => [
                             'given_name' => 'John',
                             'family_name' => 'Doe',
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
        ->get(route('google.callback', ['state' => $state]))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('settings/connected-accounts')
                ->where('errors', [])
        );

    $user->refresh();

    expect($user->connectedAccounts()->first()->service)->toBe('google');
});

it('user can delete Google auth account', function () {
    $user = createUser();

    $connection = $user
        ->connectedAccounts()
        ->create([
                     'service' => 'google',
                     'user_id' => $user->id,
                     'identifier' => $user->email,
                 ]);

    $this->actingAs($user)->get(route('connected-accounts.edit'));

    $this
        ->actingAs($user)
        ->followingRedirects()
        ->delete(route('connected-accounts.destroy', $connection->id))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('settings/connected-accounts')
                ->where('errors', [])
        );

    expect($user->connectedAccounts()->first())->toBeNull();
});
