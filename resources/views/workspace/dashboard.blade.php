@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome, {{ auth()->user()->name }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
            class="group p-5 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center">
                    <i data-lucide="folder-kanban" class="w-4 h-4 text-gray-400"></i>
                </div>
                <i data-lucide="arrow-up-right"
                    class="w-4 h-4 text-gray-700 group-hover:text-gray-400 transition-colors"></i>
            </div>
            <p class="text-white text-2xl font-bold tracking-tight">{{ $projectCount }}</p>
            <p class="text-gray-500 text-sm">Projects</p>
        </a>

        <a href="{{ route('workspace.members', $currentWorkspace->slug) }}"
            class="group p-5 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                </div>
                <i data-lucide="arrow-up-right"
                    class="w-4 h-4 text-gray-700 group-hover:text-gray-400 transition-colors"></i>
            </div>
            <p class="text-white text-2xl font-bold tracking-tight">{{ $memberCount }}</p>
            <p class="text-gray-500 text-sm">Members</p>
        </a>

        <div class="p-5 bg-gray-900 border border-gray-800 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
            <p class="text-white text-2xl font-bold tracking-tight">{{ $myTaskCount }}</p>
            <p class="text-gray-500 text-sm">My Tasks</p>
        </div>

        <div class="p-5 bg-gray-900 border {{ $overdueCount > 0 ? 'border-red-500/30' : 'border-gray-800' }} rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div
                    class="w-9 h-9 rounded-lg {{ $overdueCount > 0 ? 'bg-red-500/10' : 'bg-gray-800' }} flex items-center justify-center">
                    <i data-lucide="alert-triangle"
                        class="w-4 h-4 {{ $overdueCount > 0 ? 'text-red-400' : 'text-gray-400' }}"></i>
                </div>
            </div>
            <p class="text-white text-2xl font-bold tracking-tight">{{ $overdueCount }}</p>
            <p class="text-gray-500 text-sm">Overdue</p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-2xl">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-white text-sm font-semibold">My Tasks</h2>
                <span class="text-gray-600 text-xs">{{ $myTasks->count() }} active</span>
            </div>

            @if($myTasks->isEmpty())
                <div class="text-center py-16 px-6">
                    <p class="text-gray-600 text-sm">No tasks assigned to you</p>
                </div>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($myTasks as $task)
                        <a href="{{ route('tasks.show', [$currentWorkspace->slug, $task->project_id, $task->id]) }}"
                            class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-800/50 transition-colors">

                            <div
                                class="w-2 h-2 rounded-full shrink-0 {{ $task->priority->value === 'high' ? 'bg-red-400' : '' }} {{ $task->priority->value === 'medium' ? 'bg-yellow-400' : '' }} {{ $task->priority->value === 'low' ? 'bg-gray-600' : '' }}">
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-white text-sm truncate">{{ $task->title }}</p>
                                <p class="text-gray-600 text-xs mt-0.5">{{ $task->project->name }}</p>
                            </div>

                            <span class="text-xs px-2 py-1 rounded-md shrink-0
                                                                            {{ $task->status->value === 'todo' ? 'bg-gray-800 text-gray-400' : '' }}
                                                                            {{ $task->status->value === 'in_progress' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                                                        ">
                                {{ $task->status->value === 'in_progress' ? 'In Progress' : 'Todo' }}
                            </span>

                            @if($task->due_date)
                                <span class="text-xs shrink-0 {{ $task->due_date->isPast() ? 'text-red-400' : 'text-gray-600' }}">
                                    {{ $task->due_date->format('M d') }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-800">
                <h2 class="text-white text-sm font-semibold">Recent Activity</h2>
            </div>

            @if($activities->isEmpty())
                <div class="text-center py-16 px-5">
                    <p class="text-gray-600 text-sm">No activity yet</p>
                </div>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($activities as $activity)
                        <div class="px-5 py-3.5">
                            <p class="text-gray-300 text-sm leading-relaxed">
                                <span class="text-white font-medium">{{ $activity->user->name }}</span>
                                {{ $activity->description }}
                            </p>
                            <p class="text-gray-600 text-xs mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection