<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkspaceRequest;
use App\Models\Workspace;
use App\Enums\WorkspaceRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WorkspaceController extends Controller
{
    public function create(): View
    {
        return view('workspace.create');
    }

    public function index(): View
    {
        $workspaces = auth()->user()->workspaces()->get();
        return view('workspace.index', compact('workspaces'));
    }

    public function dashboard(): View
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        $projectCount = Project::count();
        $memberCount = $workspace->members()->count();
        $myTaskCount = Task::where('assigned_to', auth()->id())->count();
        $activities = Activity::with('user')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('workspace.dashboard', compact('projectCount', 'memberCount', 'myTaskCount', 'activities'));
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
