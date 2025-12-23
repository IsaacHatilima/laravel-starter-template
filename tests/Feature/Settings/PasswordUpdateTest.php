<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('password update page is displayed', function () {
    $user = createUser();

    $this
        ->actingAs($user)
        ->get(route('user-password.edit'))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('settings/password')
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

test('password can be updated', function () {
    $user = createUser();

    $response = $this
        ->actingAs($user)
        ->withHeader('X-Inertia', 'true')
        ->from(route('user-password.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'Password1#',
            'password' => 'Password12#',
            'password_confirmation' => 'Password12#',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('user-password.edit'))
        ->assertSessionHas('inertia.flash_data.status', 'success')
        ->assertSessionHas('inertia.flash_data.message', 'Password updated successfully.');

    expect(Hash::check('Password12#', $user->refresh()->password))->toBeTrue();
});

test('password cannot be updated wrong format', function () {
    $user = createUser();

    $response = $this
        ->actingAs($user)
        ->from(route('user-password.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'Passsword1#',
            'password' => 'Password12',
            'password_confirmation' => 'Password12',
        ]);

    $response
        ->assertSessionHasErrors(['password'])
        ->assertRedirect(route('user-password.edit'));

    expect(Hash::check('Password1#', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('user-password.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'Password12#',
            'password_confirmation' => 'Password12#',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('user-password.edit'));
});
