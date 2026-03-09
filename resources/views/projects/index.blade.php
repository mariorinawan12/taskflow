@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">Projects</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
            </div>
            <a href="{{ route('projects.create', $currentWorkspace->slug) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                New Project
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/10 border border-lime-500/20 rounded-xl">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="text-center py-20 bg-gray-900 border border-gray-800 rounded-2xl">
                <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="folder-plus" class="w-5 h-5 text-gray-600"></i>
                </div>
                <p class="text-gray-400 text-sm">No projects yet</p>
                <p class="text-gray-600 text-xs mt-1">Create your first project to get started</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                        class="block p-5 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-all duration-150 group">

                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center shrink-0">
                                    <i data-lucide="folder-kanban"
                                        class="w-4 h-4 text-gray-500 group-hover:text-gray-400 transition-colors"></i>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-white text-sm font-semibold truncate">{{ $project->name }}</h2>
                                    <span
                                        class="text-[11px] font-medium
                                                    {{ $project->status->value === 'active' ? 'text-lime-400' : 'text-gray-500' }}">
                                        {{ ucfirst($project->status->value) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($project->description)
                            <p class="text-gray-500 text-xs leading-relaxed mb-4 line-clamp-2">{{ $project->description }}</p>
                        @endif

                        {{-- Progress bar --}}
                        @if($project->tasks_count > 0)
                            <div class="mb-3">
                                <div class="flex h-1.5 rounded-full overflow-hidden bg-gray-800">
                                    @if($project->done_count > 0)
                                        <div class="bg-lime-500 rounded-l-full"
                                            style="width: {{ ($project->done_count / $project->tasks_count) * 100 }}%"></div>
                                    @endif
                                    @if($project->in_progress_count > 0)
                                        <div class="bg-blue-400"
                                            style="width: {{ ($project->in_progress_count / $project->tasks_count) * 100 }}%"></div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Stats --}}
                        <div class="flex items-center gap-3 text-[11px]">
                            <span class="text-gray-500">{{ $project->tasks_count }} tasks</span>
                            @if($project->tasks_count > 0)
                                <span class="text-gray-700">·</span>
                                <span class="text-lime-400/70">{{ $project->done_count }} done</span>
                                <span class="text-gray-700">·</span>
                                <span class="text-blue-400/70">{{ $project->in_progress_count }} active</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

@endsection