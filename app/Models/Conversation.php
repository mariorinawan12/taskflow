<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\WorkspaceScope;

class Conversation extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope(new WorkspaceScope());
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }
}
