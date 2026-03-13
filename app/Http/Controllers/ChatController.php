<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Project;
use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Request $request, $workspaceSlug, $type, $id)
    {
        $request->validate([
            'body' => 'required_without:attachments|nullable|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $target = match ($type) {
            'task' => Task::findOrFail($id),
            'project' => Project::findOrFail($id),
            'conversation' => Conversation::findOrFail($id),
            'workspace' => Workspace::findOrFail($id),
            default => abort(404, 'Invalid chat target type')
        };

        $workspaceId = match ($type) {
            'task' => $target->project->workspace_id,
            'project', 'conversation' => $target->workspace_id,
            'workspace' => $target->id,
        };

        $message = $target->messages()->create([
            'user_id' => auth()->id(),
            'workspace_id' => $workspaceId,
            'body' => $request->body,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('chat_attachments', 'public');

                $message->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $message->load('user', 'attachments');

        return response()->json([
            'status' => 'success',
            'message' => 'Message successfully sent',
            'data' => $message,
        ]);
    }

    public function createConversation(Request $request, $workspaceSlug)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
        ]);

        $workspaceId = session('current_workspace_id');
        $myId = auth()->id();
        $userIds = $request->user_ids;

        if (count($userIds) === 1) {
            $conversation = Conversation::where('workspace_id', $workspaceId)
                ->whereNull('name')
                ->whereHas('users', fn ($q) => $q->where('users.id', $myId))
                ->whereHas('users', fn ($q) => $q->where('users.id', $userIds[0]))
                ->whereDoesntHave('users', function ($q) use ($myId, $userIds) {
                    $q->whereNotIn('users.id', [$myId, $userIds[0]]);
                })
                ->first();
            if ($conversation) {
                return response()->json([
                    'status' => 'success',
                    'conversation_id' => $conversation->id,
                ]);
            }
        }

        $conversation = Conversation::create([
            'workspace_id' => $workspaceId,
            'name' => count($userIds) > 1 ? ($request->name ?: 'Group Chat') : null,
        ]);

        $allUserIds = array_unique(array_merge([$myId], $userIds));
        foreach ($allUserIds as $userId) {
            $conversation->users()->attach($userId, ['last_read_at' => now()]);
        }

        return response()->json([
            'status' => 'success',
            'conversation_id' => $conversation->id,
        ]);
    }

    public function loadMessages(Request $request, $workspaceSlug, $type, $id)
    {
        $modelClass = match ($type) {
            'conversations' => Conversation::class,
            'workspace' => Workspace::class,
            'task' => Task::class,
            'project' => Project::class,
            default => abort(404, 'Invalid type')
        };

        $target = $modelClass::findOrFail($id);

        if ($type === 'conversations' && ! $target->users()->where('users.id', auth()->id())->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $messages = $target->messages()
            ->with('user', 'attachments')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'user' => [
                    'id' => $m->user->id,
                    'name' => $m->user->name,
                ],
                'time' => $m->created_at->timezone('Asia/Jakarta')->format('M d, H:i'),
                'attachments' => $m->attachments->map(fn ($a) => [
                    'id' => $a->id,
                    'name' => $a->file_name,
                    'url' => asset('storage/'.$a->file_path),
                    'mime' => $a->mime_type,
                    'size' => $a->size,
                ]),
            ]);

        return response()->json([
            'status' => 'success',
            'messages' => $messages,
        ]);
    }

    public function getAttachments(Request $request, $workspaceSlug, $type, $id)
    {
        $modelClass = match ($type) {
            'conversations' => Conversation::class,
            'workspace' => Workspace::class,
            'task' => Task::class,
            'project' => Project::class,
            default => abort(404, 'Invalid type')
        };

        $target = $modelClass::findOrFail($id);

        if ($type === 'conversations' && ! $target->users()->where('users.id', auth()->id())->exists()){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $attachments = $target->messages()
            ->with('attachments')
            ->whereHas('attachments')
            ->latest()
            ->get()
            ->pluck('attachments')
            ->flatten()
            ->map(fn ($a) => [
                'id' => $a->id,
                'name' => $a->file_name,
                'url' => asset('storage/' . $a->file_path),
                'mime' => $a->mime_type,
                'size' => $a->size,
                'uploaded_at' => $a->created_at->timezone('Asia/Jakarta')->format('M d, H:i'),
            ]);

            return response()->json([
                'status' => 'success',
                'attachments' => $attachments,
            ]);
    }
}
