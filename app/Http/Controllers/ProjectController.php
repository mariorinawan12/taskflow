<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ProjectStatus;
use App\Models\Workspace;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        $project = Project::create([
            'workspace_id' => $workspace->id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => ProjectStatus::Active,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('projects.show', [
            $workspace->slug,
            $project->id,
        ]);
    }

    public function show(string $workspace, Project $project): View
    {
        $tasks = $project->tasks()->orderBy('order')->get();

        return view('projects.show', compact('project', 'tasks'));
    }

    public function edit(string $workspace, Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, string $workspace, Project $project): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        $project->update($request->validated());

        return redirect()->route('projects.show', [
            $workspace->slug,
            $project->id,
        ]);
    }

    public function archive(string $workspace, Project $project): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        $project->update(['status' => ProjectStatus::Archived]);

        return redirect()->route('projects.index', $workspace->slug);
    }


}
