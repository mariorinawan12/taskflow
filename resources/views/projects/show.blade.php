@extends('layouts.app')

@section('title', '{{ $project->name }} - {{ $currentWorkspace->name }}')

@section('content')

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
                    class="text-gray-500 hover:text-white text-sm transition-colors">
                    ← Projects
                </a>
                <h1 class="text-2xl font-bold text-white mt-2">{{ $project->name }}</h1>
                @if($project->description)
                    <p class="text-gray-500 text-sm mt-1">{{ $project->description }}</p>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('projects.edit', [$currentWorkspace->slug, $project->id]) }}"
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition-colors text-sm">
                    Edit
                </a>
                <a href="{{ route('tasks.create', [$currentWorkspace->slug, $project->id]) }}"
                    class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors text-sm">
                    + New Task
                </a>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
                <h2 class="text=sm font-semibold text-gray-400 uppercase tracking-wide mb-4">Todo</h2>
                <div class="space-y-3">
                    @foreach($tasks->filter(fn($t) => $t->status->value === 'todo') as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            class="block p-3 bg-gray-800 rounded-xl hover:bg-gray-750 transition-colors">
                            <p class="text-white text-sm">{{ $task->title }}</p>
                            @if($task->assignee)
                                <p class="text-gray-500 text-xs mt-1">{{ $task->assignee->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-4">In Progress</h2>
                <div class="space-y-3">
                    @foreach($tasks->filter(fn($t) => $t->status->value === 'in_progress') as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            class="block p-3 bg-gray-800 rounded-xl hover:bg-gray-750 transition-colors">
                            <p class="text-white text-sm">{{ $task->title }}</p>
                            @if($task->assignee)
                                <p class="text-gray-500 text-xs mt-1">{{ $task->assignee->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
                <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-4">
                    Done
                </h2>
                <div class="space-y-3">
                    @foreach($tasks->filter(fn($t) => $t->status->value === 'done') as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            class="block p-3 bg-gray-800 rounded-xl hover:bg-gray-750 transition-colors">
                            <p class="text-white text-sm">{{ $task->title }}</p>
                            @if($task->assignee)
                                <p class="text-gray-500 text-xs mt-1">{{ $task->assignee->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection