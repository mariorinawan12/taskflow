<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\WorkspaceRole;
use App\Http\Requests\StoreInvitationRequest;
use App\Jobs\SendInvitationEmail;
use App\Models\WorkspaceInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Workspace;

class MemberController extends Controller
{
    public function index(): View
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        $members = $workspace->members()
            ->withPivot('role', 'joined_at')
            ->get();

        $currentUserRole = $workspace->members()
            ->where('user_id', auth()->id())
            ->first()
            ->pivot->role;

        return view('workspace.members', compact('members', 'currentUserRole'));
    }

    public function invite(StoreInvitationRequest $request): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        $currentUserRole = $workspace->members()
            ->where('user_id', auth()->id())
            ->first()
            ->pivot->role;

        if ($currentUserRole === 'member') {
            abort(403, "Member can't invite other person");
        }

        if ($currentUserRole === 'admin' && $request->role === 'admin') {
            return back()->withErrors([
                'role' => 'Admin can only invite member.'
            ]);
        }

        $exists = WorkspaceInvitation::where('workspace_id', session('current_workspace_id'))
            ->where('email', $request->email)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'email' => 'Email has already been invited',
            ]);
        }


        $isMember = $workspace->members()
            ->where('email', $request->email)
            ->exists();

        if ($isMember) {
            return back()->withErrors([
                'email' => 'Email has become member of the workspace'
            ]);
        }

        $invitation = WorkspaceInvitation::create([
            'workspace_id' => session('current_workspace_id'),
            'invited_by' => auth()->id(),
            'email' => $request->email,
            'role' => $request->role,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ]);

        SendInvitationEmail::dispatch($invitation);

        return back()->with('success', 'Invitation successfully sent');
    }
}
