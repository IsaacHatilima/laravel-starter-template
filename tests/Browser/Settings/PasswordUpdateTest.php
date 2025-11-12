<?php

test('password can be updated', function () {
    login();

    visit(route('user-password.update'))
    ->assertSee('Update password')
    ->fill('current_password', 'Password1#')
    ->fill('password', 'Password12#')
    ->fill('password_confirmation', 'Password12#')
    ->press('Save password')
    ->assertSee('Success')
    ->assertSee('Password updated successfully.')
    ->assertUrlIs(route('user-password.update'));
});
