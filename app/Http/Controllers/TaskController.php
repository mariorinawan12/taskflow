<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function create(Project $project): View
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        $members = $workspace->members()->get();

        return view('tasks.create', compact('project', 'members'));
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        Task::create([
            'workspace_id' => $workspace->id,
            'project_id' => $project->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => TaskStatus::Todo,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('projects.show', [
            $workspace->slug,
            $project->id,
        ]);
    }

    public function show(Project $project, Task $task): View
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        $members = $workspace->members()->get();
        $comments = $task->comments()->with('author')->latest()->get();

        return view('tasks.show', compact('project', 'task', 'members', 'comments'));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        $task->update($request->validated());

        return redirect()->route('tasks.show', [
            $workspace->slug,
            $project->id,
            $task->id
        ]);
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        $task->delete();

        return redirect()->route('projects.show', [
            $workspace->slug,
            $project->id,
        ]);
    }
}
