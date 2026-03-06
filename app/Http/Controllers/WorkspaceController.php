<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkspaceRequest;
use App\Models\Workspace;
use App\Enums\WorkspaceRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function create(): View
    {
        return view('workspace.create');
    }

    public function dashboard(): View
    {
        return view('workspace.dashboard');
    }

    public function store(StoreWorkspaceRequest $request): RedirectResponse
    {
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (Workspace::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }


        $workspace = Workspace::create([
            'name' => $request->name,
            'slug' => $slug,
            'owner_id' => auth()->id(),
        ]);

        $workspace->members()->attach(auth()->id(), [
            'role' => WorkspaceRole::Owner,
            'joined_at' => now(),
        ]);

        return redirect()->route('workspace.dashboard', $workspace->slug);
    }
}
