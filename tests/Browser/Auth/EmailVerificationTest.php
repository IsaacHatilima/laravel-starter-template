<?php

test('can show email verification page', function () {
    $page = visit(route('register'));

    $page->fill('first_name', 'John')
        ->fill('last_name', 'Doe')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'Password1#')
        ->fill('password_confirmation', 'Password1#')
        ->press('Create account')
        ->assertUrlIs(route('verification.notice'))
        ->assertSee('Verify email');

    $this->assertAuthenticated();
});

test('can resend verification link', function () {
    $page = visit(route('register'));

    $page->fill('first_name', 'John')
        ->fill('last_name', 'Doe')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'Password1#')
        ->fill('password_confirmation', 'Password1#')
        ->press('Create account')
        ->assertUrlIs(route('verification.notice'))
        ->assertSee('Verify email');

    $this->assertAuthenticated();

    $page->click('Resend verification email')
        ->assertSee('A new verification link has been sent to the email address you provided during registration.');
});
