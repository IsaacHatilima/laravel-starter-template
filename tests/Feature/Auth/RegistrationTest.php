<?php

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post(route('register-user.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
        'password' => 'Password1#',
        'password_confirmation' => 'Password1#',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users cannot register with invalid password', function () {
    $response = $this->post(route('register-user.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
        'password' => 'Password1',
        'password_confirmation' => 'Password1',
    ]);

    $response->assertSessionHasErrors(['password']);
    $this->assertGuest();
});
