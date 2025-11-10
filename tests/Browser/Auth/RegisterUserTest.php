<?php

test('can show register page', function () {
    $page = visit(route('register'));

    $page->assertSee('Create an account');

    $page->assertTitleContains('Register');
});

test('user can register', function () {
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
