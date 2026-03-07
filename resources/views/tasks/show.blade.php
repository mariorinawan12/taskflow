<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $task->title }} — {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen p-8">

    <div class="max-w-3xl mx-auto">

        <div class="mb-8">
            <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                class="text-gray-500 hover:text-white text-sm transition-colors">
                ← {{ $project->name }}
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">{{ $task->title }}</h1>
            @if($task->description)
                <p class="text-gray-500 mt-2">{{ $task->description }}</p>
            @endif
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}" method="POST"
                class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                                       rounded-xl text-white focus:outline-none focus:border-lime-500">
                            <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>Todo</option>
                            <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="done" {{ $task->status->value === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Priority</label>
                        <select name="priority" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                                       rounded-xl text-white focus:outline-none focus:border-lime-500">
                            <option value="low" {{ $task->priority->value === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority->value === 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ $task->priority->value === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Assign To</label>
                    <select name="assigned_to" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                                   rounded-xl text-white focus:outline-none focus:border-lime-500">
                        <option value="">— Unassigned —</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Due Date</label>
                    <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                               rounded-xl text-white focus:outline-none focus:border-lime-500">
                </div>

                <button type="submit" class="px-4 py-2 bg-lime-500 hover:bg-lime-400
                               text-black font-semibold rounded-xl transition-colors text-sm">
                    Save
                </button>

            </form>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <h2 class="text-white font-semibold mb-4">Comments</h2>

            @if($comments->isEmpty())
                <p class="text-gray-600 text-sm">There is no comments</p>
            @else
                <div class="space-y-4 mb-6">
                    @foreach($comments as $comment)
                        <div class="border-b border-gray-800 pb-4 last:border-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-white text-sm font-medium">
                                    {{ $comment->author->name }}
                                </p>
                                <p class="text-gray-600 text-xs">
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-gray-400 text-sm">{{ $comment->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('tasks.comments.store', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                method="POST">
                @csrf
                <textarea name="body" rows="3" placeholder="Write comment" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white placeholder-gray-600
                           focus:outline-none focus:border-lime-500 mb-3"></textarea>
                <button type="submit" class="px-4 py-2 bg-lime-500 hover:bg-lime-400
                               text-black font-semibold rounded-xl transition-colors text-sm">
                    Send
                </button>
            </form>
        </div>

        <form action="{{ route('tasks.destroy', [$currentWorkspace->slug, $project->id, $task->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Delete this task?')"
                class="text-red-500 hover:text-red-400 text-sm transition-colors">
                Delete Task
            </button>
        </form>

    </div>

</body>

</html>