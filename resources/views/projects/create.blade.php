@extends('layouts.app')

@section('title', 'Create Project')

@section('content')

    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Create Project</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('projects.store', $currentWorkspace->slug) }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Project Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Input project name"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:borderlime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="Optional"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:border-lime-500">
                                {{ old('description') }}
                            </textarea>
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors">
                Create Project
            </button>
        </form>

        <div class="mt-6">
            <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
                class="text-gray-500 hover:text-white text-sm transition-colors">
                ← Back to Projects
            </a>
        </div>

    </div>

@endsection