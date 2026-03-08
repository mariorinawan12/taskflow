@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">Projects</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
            </div>
            <a href="{{ route('projects.create', $currentWorkspace->slug) }}"
                class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors text-sm">
                + New Project
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/10 border border-lime-500/20 rounded-xl">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="text-center py-16 text-gray-600">
                <p class="text-lg">There is no project</p>
                <p class="text-sm mt-1">Create your first project</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                        class="block p-5 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-white font-medium">{{ $project->name }}</h2>
                                @if($project->description)
                                    <p class="text-gray-500 text-sm mt-1">
                                        {{ $project->description }}
                                    </p>
                                @endif
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $project->status->value === 'active' ? 'bg-lime-500/10 text-lime-400' : 'bg-gray-800 text-gray-500' }}">
                                {{ $project->status->value }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>


@endsection