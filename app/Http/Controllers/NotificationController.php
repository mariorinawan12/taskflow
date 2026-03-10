<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $workspaceId = session('current_workspace_id');

        $notifications = auth()->user()
            ->notifications()
            ->whereRaw("(data::jsonb->>'workspace_id')::integer = ?", [$workspaceId])
            ->latest()
            ->paginate(20);

        auth()->user()
            ->unreadNotifications()
            ->whereRaw("(data::jsonb->>'workspace_id')::integer = ?", [$workspaceId])
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }



    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->where('id', $id)->delete();

        return back();
    }
}
