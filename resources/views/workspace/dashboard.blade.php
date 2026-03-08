@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Welcome, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-3 gap-6">
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

@endsection