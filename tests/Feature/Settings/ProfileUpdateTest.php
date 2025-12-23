<?php

use Inertia\Testing\AssertableInertia as Assert;

test('profile page is displayed', function () {
    $user = createUser();

    $this
        ->actingAs($user)
        ->get(route('profile.edit'))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('settings/profile')
                ->has(
                    'auth.user',
                    fn (Assert $page) => $page
                        ->where('email', $user->email)
                        ->where('profile.first_name', $user->profile->first_name)
                        ->where('profile.last_name', $user->profile->last_name)
                        ->etc()
                )
        );
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = createUser();

    $this->actingAs($user)->get(route('profile.edit'));

    $response = $this
        ->actingAs($user)
        ->withHeader('X-Inertia', 'true')
        ->patch(route('profile.update'), [
            'email' => 'test@mail.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

    $response
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('inertia.flash_data.status', 'success')
        ->assertSessionHas('inertia.flash_data.message', 'Profile updated successfully.');

    $user->refresh();

    expect($user->email)->toBe('test@mail.com')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->profile->first_name)->toBe('John')
        ->and($user->profile->last_name)->toBe('Doe');
});

test('email verification status is changed when the email address is unchanged', function () {
    $user = createUser();

    $this->actingAs($user)->get(route('profile.edit'));

    $response = $this
        ->actingAs($user)
        ->withHeader('X-Inertia', 'true')
        ->patch(route('profile.update', $user->profile->id), [
            'email' => 'john.doe@mail.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

    $response
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('inertia.flash_data.status', 'success')
        ->assertSessionHas('inertia.flash_data.message', 'Profile updated successfully.');

    $user->refresh();

    expect($user->email)->toBe('john.doe@mail.com')
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->profile->first_name)->toBe('John')
        ->and($user->profile->last_name)->toBe('Doe');
});

test('user can delete their account', function () {
    $user = createUser();

    $this->actingAs($user)->get(route('profile.edit'));

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'Password1#',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('correct password must be provided to delete account', function () {
    $user = createUser();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh())->not->toBeNull();
});
