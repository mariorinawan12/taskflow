<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;

class ProjectPolicy
{
    private function getUserRole(User $user): string
    {
        $workspace = Workspace::find(session('current_workspace_id'));
        if (!$workspace)
            return null;


        return $workspace->members()
            ->where('user_id', $user->id)
            ->first()
            ->pivot->role;
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        $role = $this->getUserRole($user);
        return in_array($role, ['owner', 'admin']);
    }

    public function update(User $user, Project $project): bool
    {
        $role = $this->getUserRole($user);
        return in_array($role, ['owner', 'admin']);
    }

    public function archive(User $user, Project $project): bool
    {
        $role = $this->getUserRole($user);
        return in_array($role, ['owner', 'admin']);
    }

    public function delete(User $user, Project $project): bool
    {
        $role = $this->getUserRole($user);
        return $role === 'owner';
    }
}