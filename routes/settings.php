<?php

use App\Http\Controllers\Settings\ConnectedAccountsController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');

    Route::get('settings/connected-accounts', [ConnectedAccountsController::class, 'edit'])
        ->name('connected-accounts.edit');

    Route::get('settings/connected-accounts/redirect', [ConnectedAccountsController::class, 'redirectToGoogle'])
        ->name('connected-accounts.redirectToGoogle');
    Route::delete(
        'settings/connected-accounts/delete/{connectedAccount}',
        [ConnectedAccountsController::class, 'destroy']
    )
        ->name('connected-accounts.destroy');
});
