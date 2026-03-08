<?php

namespace App\Policies;

use App\Enums\WorkspaceRole;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;

class TaskPolicy
{
    private function getUserRole(User $user): string
    {
        $workspace = Workspace::find(session('current_workspace_id'));

        return $workspace->members()
            ->where('user_id', $user->id)
            ->first()
            ->pivot->role;
    }

    public function view(User $user, Task $task): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return true;
    }

    public function delete(User $user, Task $task): bool
    {
        $role = $this->getUserRole($user);

        return $task->created_by === $user->id
            || $role === 'owner'
            || $role === 'admin';
    }
}