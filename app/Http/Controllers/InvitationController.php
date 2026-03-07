<?php

namespace App\Http\Controllers;

use App\Enums\WorkspaceRole;
use App\Models\User;
use App\Models\WorkspaceInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = WorkspaceInvitation::where('token', $token)
            ->firstOrFail();

        if (!$invitation->isValid()) {
            abort(404, 'Invitation is not valid or has been expired');
        }

        return view('invitations.accept', compact('invitation'));
    }

    public function accept(string $token): RedirectResponse
    {
        $invitation = WorkspaceInvitation::where('token', $token)
            ->firstOrFail();

        if (!$invitation->isValid()) {
            abort(404, 'Invitation is not valid or has been expired');
        }

        if (!auth()->check()) {
            session(['invitation_token' => $token]);

            $userExists = User::where('email', $invitation->email)->exists();

            return $userExists ? redirect()->route('login') : redirect()->route('register');
        }

        $this->joinWorkspace($invitation);

        return redirect()->route('workspace.dashboard', $invitation->workspace->slug);
    }

    private function joinWorkspace(WorkspaceInvitation $invitation): void
    {
        $alreadyMember = $invitation->workspace->members()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$alreadyMember) {
            $invitation->workspace->members()->attach(auth()->id(), [
                'role' => $invitation->role,
                'joined_at' => now(),
            ]);
        }
        $invitation->update(['accepted_at' => now()]);
    }
}
