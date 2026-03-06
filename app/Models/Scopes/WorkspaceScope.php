<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WorkspaceScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (session()->has('current_workspace_id')) {
            $builder->where(
                'workspace_id',
                session('current_workspace_id')
            );
        }
    }
}