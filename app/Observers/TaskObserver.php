<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\TaskAssigned;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Activity::create([
            'workspace_id' => $task->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Task',
            'subject_id' => $task->id,
            'description' => auth()->user()->name . ' created task "' . $task->title . '"',
            'properties' => null
        ]);


        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignee = User::find($task->assigned_to);
            $assignee->notify(new TaskAssigned($task, auth()->user()->name));
        }

    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $changes = $task->getChanges();
        unset($changes['updated_at']);

        if (empty($changes))
            return;

        $descriptions = [];

        if (isset($changes['status'])) {
            $descriptions[] = 'status to "' . $changes['status'] . '"';
        }
        if (isset($changes['assigned_to'])) {
            $descriptions[] = 'assignee is changed';

            if ($changes['assigned_to'] && $changes['assigned_to'] !== auth()->id()) {
                $assignee = User::find($changes['assigned_to']);
                $assignee->notify(new TaskAssigned($task, auth()->user()->name));
            }
        }
        if (isset($changes['priority'])) {
            $descriptions[] = 'priority to "' . $changes['priority'] . '"';
        }
        if (isset($changes['title'])) {
            $descriptions[] = 'title to "' . $changes['title'] . '"';
        }

        if (empty($descriptions)) {
            return;
        }

        Activity::create([
            'workspace_id' => $task->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Task',
            'subject_id' => $task->id,
            'description' => auth()->user()->name . ' changed ' . implode(', ', $descriptions) . ' on task "' . $task->title . '"',
            'properties' => $changes,
        ]);
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        Activity::create([
            'workspace_id' => $task->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Task',
            'subject_id' => $task->id,
            'description' => auth()->user()->name . ' deleted task "' . $task->title . '"',
            'properties' => null,
        ]);
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
