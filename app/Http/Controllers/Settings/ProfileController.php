<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Profile\UpdateProfileAction;
use App\Dtos\ProfileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrentPasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $this->currentUser();

        $this->authorize('view', $user->profile);

        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(
        ProfileUpdateRequest $request,
        UpdateProfileAction $action
    ): RedirectResponse {
        $user = $this->currentUser();

        $this->authorize('update', $user->profile);

        $dto = ProfileDto::fromUpdateRequest($request);

        $email = trim(Str::lower($request->string('email')->value()));

        $action->execute($user->profile, $dto, $email);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(CurrentPasswordRequest $request): RedirectResponse
    {
        $user = $this->currentUser();

        $this->authorize('delete', $user->profile);

        $user->delete();

        /** @var StatefulGuard $guard */
        $guard = Auth::guard('web');
        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->with('success', 'Profile deleted successfully. Goodbye!');
    }
}
