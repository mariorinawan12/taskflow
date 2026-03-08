<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskCommentRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Workspace;
use App\Notifications\CommentAdded;
use Illuminate\Http\RedirectResponse;
class TaskCommentController extends Controller
{
    public function store(StoreTaskCommentRequest $request, string $workspace, Project $project, Task $task): RedirectResponse
    {
        $this->authorize('create', TaskComment::class);
        $workspace = Workspace::find(session('current_workspace_id'));



        $comment = TaskComment::create([
            'workspace_id' => $workspace->id,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $task->assignee->notify(new CommentAdded($comment, auth()->user()->name));
        }

        if ($task->created_by !== auth()->id()) {
            $task->creator->notify(new CommentAdded($comment, auth()->user()->name));
        }

        return redirect()->route('tasks.show', [
            $workspace->slug,
            $project->id,
            $task->id,
        ]);
    }

    public function destroy(string $workspace, Project $project, Task $task, TaskComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $workspace = Workspace::find(session('current_workspace_id'));

        if ($comment->user_id !== auth()->id()) {
            abort(403, 'You have no access to delete comment.');
        }

        $comment->delete();

        return redirect()->route('tasks.show', [
            $workspace->slug,
            $project->id,
            $task->id,
        ]);
    }
}
