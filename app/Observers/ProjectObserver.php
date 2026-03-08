<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\Activity;


class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        Activity::create([
            'workspace_id' => $project->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Project',
            'subject_id' => $project->id,
            'description' => auth()->user()->name . ' create project "' . $project->name . '"',
            'properties' => null,
        ]);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $changes = $project->getChanges();
        unset($changes['updated_at']);

        if (empty($changes))
            return;

        $descriptions = [];

        if (isset($changes['name'])) {
            $descriptions[] = 'name to "' . $changes['name'] . '"';
        }
        if (isset($changes['status'])) {
            $descriptions[] = 'status to "' . $changes['status'] . '"';
        }

        if (empty($descriptions))
            return;

        Activity::create([
            'workspace_id' => $project->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Project',
            'subject_id' => $project->id,
            'description' => auth()->user()->name . ' changed ' . implode(', ', $descriptions),
            'properties' => $changes
        ]);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        Activity::create([
            'workspace_id' => $project->workspace_id,
            'user_id' => auth()->id(),
            'subject_type' => 'Project',
            'subject_id' => $project->id,
            'description' => auth()->user()->name . ' delete project "' . $project->name . '"',
            'properties' => null,
        ]);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
