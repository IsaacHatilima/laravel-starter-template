<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Log;
use Throwable;

class RegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(
        RegisterRequest $request,
        RegisterAction $registerAction
    ): RedirectResponse {
        try {
            $user = $registerAction->execute($request);

            Auth::login($user);
            $user->update(['last_login_at' => now()]);

            return to_route('dashboard')->with('success', 'Welcome aboard!');
        } catch (Throwable $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
    }
}
