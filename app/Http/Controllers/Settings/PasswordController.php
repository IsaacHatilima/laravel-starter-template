<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Profile\UpdatePasswordAction;
use App\DTOs\Command\Settings\ChangePasswordRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    public function __construct(private readonly UpdatePasswordAction $action)
    {
    }

    /**
     * Show the user's password settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('settings/password');
    }

    /**
     * Update the user's password.
     */
    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $dto = ChangePasswordRequestDTO::fromRequest($request);

        $this->action->execute($dto, $this->currentUser());

        Inertia::flash([
            'status' => 'success',
            'message' => 'Password updated successfully.',
        ]);

        return back();
    }
}
