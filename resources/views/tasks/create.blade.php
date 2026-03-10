@extends('layouts.app')

@section('title', 'Create Task')

@section('content')

    <div class="min-h-[80vh] flex items-center justify-center">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">

                <div class="mb-5">
                    <h1 class="text-lg font-semibold text-white">Create Task</h1>
                    <p class="text-gray-500 text-xs mt-1">{{ $project->name }}</p>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                        @foreach($errors->all() as $error)
                            <p class="text-red-400 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('tasks.store', [$currentWorkspace->slug, $project->id]) }}" method="POST"
                    class="space-y-4">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Fix login bug"
                            autofocus
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Description <span
                                class="text-gray-700">(optional)</span></label>
                        <textarea name="description" rows="3" placeholder="Add more details about this task..."
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500 resize-none">{{ old('description') }}</textarea>
                    </div>

                    {{-- Priority & Due Date — side by side --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Priority</label>
                            <select name="priority"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium
                                </option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Due Date</label>
                            <input type="date" name="due_date" value="{{ old('due_date') }}"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                        </div>
                    </div>

                    {{-- Assignee --}}
                    <div class="relative" x-data="{open: false}">
                        <label class="block text-xs text-gray-500 mb-1.5">Assign To</label>

                        <button type="button" @click="open = !open" @click.away="open = false"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-left text-white focus:outline-none focus:border-gray-500 flex justify-between items-center">
                            <span class="text-gray-400">Select assignees...</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="open" style="display: none;" @click.stop
                            class="absolute z-10 w-full mt-1 bg-gray-800 border border-gray-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <div class="p-2 space-y-1">
                                <label class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer">
                                    <input type="checkbox" name="assignees[]" value="{{ auth()->id() }}"
                                        class="rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 bg-gray-900"
                                        {{ in_array(auth()->id(), old('assignees', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-white">
                                            Assign to me ({{ auth()->user()->name }})
                                        </span>
                                </label>
                                <div class="h-px bg-gray-700 my-1"></div>
                                <div class="px-2 py-1 text-xs font-semibold text-gray-500">Members</div>

                                @foreach($members as $member)
                                    @if($member->id !== auth()->id())
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded cursor-pointer">
                                            <input type="checkbox" name="assignees[]" value="{{ $member->id }}"
                                                class="rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 bg-gray-900"
                                                    {{ in_array($member->id, old('assignees', [])) ? 'checked' : '' }}>
                                                    <span class="text-sm text-gray-300">{{ $member->name }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                            class="px-4 py-2 text-gray-400 hover:text-white text-sm transition-colors rounded-lg hover:bg-gray-800">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection