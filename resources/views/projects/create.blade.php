@extends('layouts.app')

@section('title', 'Create Project')

@section('content')

    <div class="min-h-[80vh] flex items-center justify-center">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

                <div class="mb-5">
                    <h1 class="text-lg font-semibold text-white">Create Project</h1>
                    <p class="text-gray-500 text-xs mt-1">Add a new project to {{ $currentWorkspace->name }}</p>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                        @foreach($errors->all() as $error)
                            <p class="text-red-400 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('projects.store', $currentWorkspace->slug) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Website Redesign"
                            autofocus
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Description <span
                                class="text-gray-700">(optional)</span></label>
                        <textarea name="description" rows="3" placeholder="What's this project about?"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500 resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
                            class="px-4 py-2 text-gray-400 hover:text-white text-sm transition-colors rounded-lg hover:bg-gray-800">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection