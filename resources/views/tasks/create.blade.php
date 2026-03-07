<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task — {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">

        <div class="mb-8">
            <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                class="text-gray-500 hover:text-white text-sm transition-colors">
                ← {{ $project->name }}
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Create Task</h1>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('tasks.store', [$currentWorkspace->slug, $project->id]) }}" method="POST"
            class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Task Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Input task title" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white placeholder-gray-600
                           focus:outline-none focus:border-lime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="Optional" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white placeholder-gray-600
                           focus:outline-none focus:border-lime-500">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Priority</label>
                <select name="priority" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Assign To</label>
                <select name="assigned_to" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
                    <option value="">— Unassigned —</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <button type="submit" class="w-full py-2.5 bg-lime-500 hover:bg-lime-400
                       text-black font-semibold rounded-xl transition-colors">
                Create Task
            </button>

        </form>

    </div>

</body>

</html>