@extends('layouts.app')

@section('title', $project->name)

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
                class="inline-flex items-center gap-1 text-gray-500 hover:text-white text-sm transition-colors">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Projects
            </a>
            <h1 class="text-xl font-bold text-white mt-2">{{ $project->name }}</h1>
            @if($project->description)
                <p class="text-gray-500 text-sm mt-1">{{ $project->description }}</p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('projects.edit', [$currentWorkspace->slug, $project->id]) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors text-sm">
                <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                Settings
            </a>
            <a href="{{ route('tasks.create', [$currentWorkspace->slug, $project->id]) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                New Task
            </a>
        </div>
    </div>

    {{-- Kanban Board --}}
    @php
        $columns = [
            ['key' => 'todo', 'label' => 'Todo', 'color' => 'gray'],
            ['key' => 'in_progress', 'label' => 'In Progress', 'color' => 'blue'],
            ['key' => 'done', 'label' => 'Done', 'color' => 'lime'],
        ];
    @endphp

    <div class="grid grid-cols-3 gap-5">
        @foreach($columns as $col)
            @php
                $columnTasks = $tasks->filter(fn($t) => $t->status->value === $col['key']);
            @endphp

            <div class="flex flex-col">
                {{-- Column Header --}}
                <div class="flex items-center gap-2 mb-3 px-1">
                    <div class="w-2 h-2 rounded-full
                                        {{ $col['color'] === 'gray' ? 'bg-gray-500' : '' }}
                                        {{ $col['color'] === 'blue' ? 'bg-blue-400' : '' }}
                                        {{ $col['color'] === 'lime' ? 'bg-lime-400' : '' }}
                                    "></div>
                    <h2 class="text-sm font-medium text-gray-400">{{ $col['label'] }}</h2>
                    <span class="text-xs text-gray-600">{{ $columnTasks->count() }}</span>
                </div>

                {{-- Column Body --}}
                <div class="flex-1 space-y-2">
                    @foreach($columnTasks as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            class="block p-3.5 bg-gray-900 border border-gray-800 rounded-xl hover:border-gray-700 transition-all duration-150">

                            {{-- Title --}}
                            <p class="text-white text-sm leading-snug">{{ $task->title }}</p>

                            {{-- Meta row --}}
                            <div class="flex items-center gap-2 mt-2.5">
                                {{-- Priority badge --}}
                                <span class="text-[11px] px-1.5 py-0.5 rounded font-medium
                                                            {{ $task->priority->value === 'high' ? 'bg-red-500/10 text-red-400' : '' }}
                                                            {{ $task->priority->value === 'medium' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                                            {{ $task->priority->value === 'low' ? 'bg-gray-800 text-gray-500' : '' }}
                                                        ">
                                    {{ ucfirst($task->priority->value) }}
                                </span>

                                {{-- Due date --}}
                                @if($task->due_date)
                                    <span
                                        class="text-[11px] {{ $task->due_date->isPast() && $col['key'] !== 'done' ? 'text-red-400' : 'text-gray-600' }}">
                                        {{ $task->due_date->format('M d') }}
                                    </span>
                                @endif

                                {{-- Spacer --}}
                                <div class="flex-1"></div>

                                {{-- Assignee avatar --}}
                                @if($task->assignee)
                                    <div class="w-5 h-5 rounded-full bg-gray-700 flex items-center justify-center text-[10px] font-medium text-gray-300"
                                        title="{{ $task->assignee->name }}">
                                        {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach

                    {{-- Empty state --}}
                    @if($columnTasks->isEmpty())
                        <div class="border border-dashed border-gray-800 rounded-xl p-6 text-center">
                            <p class="text-gray-700 text-xs">No tasks</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

@endsection