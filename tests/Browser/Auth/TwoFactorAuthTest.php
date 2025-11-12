<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('user can authenticate with two factor auth', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    /** @var User $user */
    $user = createUser();
    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', 'Password1#')
        ->press('Log in')
        ->assertUrlIs(route('two-factor.login'))
        ->assertSee('Authentication code');

    visit(route('two-factor.login'))
        ->assertUrlIs(route('two-factor.login'))
        ->assertSee('Authentication code')
        ->click('login using a recovery code')
        ->fill('recovery_code', 'code1')
        ->press('Continue')
        ->assertUrlIs(route('dashboard'));
});

test('user cannot authenticate with wrong two factor auth codes', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
                                          'confirm' => true,
                                          'confirmPassword' => true,
                                      ]);

    /** @var User $user */
    $user = createUser();
    $user->forceFill([
                         'two_factor_secret' => encrypt('test-secret'),
                         'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
                         'two_factor_confirmed_at' => now(),
                     ])->save();

    visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', 'Password1#')
        ->press('Log in')
        ->assertUrlIs(route('two-factor.login'))
        ->assertSee('Authentication code');

    visit(route('two-factor.login'))
        ->assertUrlIs(route('two-factor.login'))
        ->assertSee('Authentication code')
        ->click('login using a recovery code')
        ->fill('recovery_code', 'wrong-code')
        ->press('Continue')
        ->assertUrlIs(route('two-factor.login'))
        ->assertSee('The provided two factor recovery code was invalid.');
});
