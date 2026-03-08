@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
            class="p-6 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-colors">
            <p class="text-gray-400 text-sm mb-1">Projects</p>
            <p class="text-white text-2xl font-bold">{{ $projectCount }}</p>
        </a>

        <a href="{{ route('workspace.members', $currentWorkspace->slug) }}"
            class="p-6 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-colors">
            <p class="text-gray-400 text-sm mb-1">Members</p>
            <p class="text-white text-2xl font-bold">{{ $memberCount }}</p>
        </a>

        <div class="p-6 bg-gray-900 border border-gray-800 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">My Tasks</p>
            <p class="text-white text-2xl font-bold">{{ $myTaskCount }}</p>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
        <h2 class="text-white font-semibold mb-4">Recent Activity</h2>
        @if($activities->isEmpty())
            <p class="text-gray-600 text-sm">There is no activity</p>
        @else
            <div class="space-y-4">
                @foreach($activities as $activity)
                    <div class="flex items-start gap-3 pb-4 border-b border-gray-800 last:border-0 last:pb-0">
                        <div
                            class="w-7 h-7 rounded-full bg-gray-800 flex items-center justify-center text-xs font-bold text-lime-400 shrink-0">
                            {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-gray-300 text-sm">{{ $activity->description }}</p>
                            <p class="text-gray-600 text-xs mt-0.5">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection