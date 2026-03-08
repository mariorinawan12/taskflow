<?php

namespace App\Policies;

use App\Models\TaskComment;
use App\Models\User;
use App\Models\Workspace;

class TaskCommentPolicy
{
    private function getUserRole(User $user): string
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        return $workspace->members()
            ->where('user_id', $user->id)
            ->first()
            ->pivot->role;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, TaskComment $comment): bool
    {
        $role = $this->getUserRole($user);
        return $comment->user_id === $user->id
            || $role === 'owner'
            || $role === 'admin';
    }
}