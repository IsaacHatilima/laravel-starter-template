<?php



test('password reset can be requested', function () {
    $user = createUser();

    visit(route('password.request'))
        ->assertSee('Forgot password')
        ->fill('email', $user->email)
        ->press('Email password reset link')
        ->assertSee('We have emailed your password reset link.');
});
