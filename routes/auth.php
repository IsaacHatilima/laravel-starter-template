<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register-user', [RegisterController::class, 'store'])->name('register-user.store');
