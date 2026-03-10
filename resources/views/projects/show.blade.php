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
                <div class="flex-1 space-y-2 kanban-column" data-status="{{ $col['key'] }}">
                    @foreach($columnTasks as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            class="block p-3.5 bg-gray-900 border border-gray-800 rounded-xl hover:border-gray-700 transition-all duration-150 kanban-card"
                            data-task-id="{{ $task->id }}">

                            {{-- Title --}}
                            <p class="text-white text-sm leading-snug">{{ $task->title }}</p>

                            {{-- Meta row --}}
                            <div class="flex items-center gap-2 mt-2.5">
                                {{-- Priority badge --}}
                                <span
                                    class="text-[11px] px-1.5 py-0.5 rounded font-medium
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
                                <div class="flex items-center -space-x-2">
                                    @foreach($task->assignees->take(3) as $assignee)
                                        <div class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-[10px] font-bold border-2 border-gray-900 ring-1 ring-gray-800 relative"
                                            style="z-index: {{ 10 - $loop->index }}" title="{{ $assignee->name }}">
                                            {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                        </div>
                                    @endforeach

                                    @if($task->assignees->count() > 3)
                                        <div class="w-6 h-6 rounded-full bg-gray-800 text-gray-400 items-center justify-center text-[10px] font-bold border-2 border-gray-900 relative"
                                            style="z-index: 0;">
                                            +{{ $task->assignees->count() - 3 }}
                                        </div>
                                    @endif

                                    @if($task->assignees->isEmpty())
                                        <div class="w-6 h-6 rounded-full border border-dashed border-gray-600 flex items-center justify-center bg-gray-800/50"
                                            title="Unassigned">
                                            <i data-lucide="user" class="w-3 h-3 text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach

                    {{-- Empty state --}}
                    <div class="border border-dashed border-gray-800 rounded-xl p-6 text-center empty-state"
                        style="{{ $columnTasks->isEmpty() ? '' : 'display:none' }}">
                        <p class="text-gray-700 text-xs">No tasks</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        document.querySelectorAll('.kanban-column').forEach(column => {
            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'opacity-30',
                draggable: '.kanban-card',
                onEnd: function (evt) {
                    const taskId = evt.item.dataset.taskId;
                    const newStatus = evt.to.dataset.status;
                    const projectId = '{{ $project->id }}';

                    evt.item.addEventListener('click', function (e) {
                        if (evt.item.dataset.dragged === 'true') {
                            e.preventDefault();
                            evt.item.dataset.dragged = 'false';
                        }
                    }, { once: true });

                    fetch(`/{{ $currentWorkspace->slug }}/projects/${projectId}/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                        .then(res => res.json())
                        .then(data => {
                            document.querySelectorAll('.kanban-column').forEach(col => {
                                const cards = col.querySelectorAll('.kanban-card').length;
                                const emptyState = col.parentElement.querySelector('.empty-state');

                                col.closest('.flex.flex-col').querySelector('.text-xs.text-gray-600').textContent = cards;

                                if (emptyState) {
                                    emptyState.style.display = cards > 0 ? 'none' : 'block';
                                }
                            })
                        })
                },
                onStart: function (evt) {
                    evt.item.dataset.dragged = 'true';
                }
            });
        });

    </script>

@endsection