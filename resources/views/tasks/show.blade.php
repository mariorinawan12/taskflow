@extends('layouts.app')

@section('title', $task->title)

@section('content')

    {{-- Back link --}}
    <div class="mb-6">
        <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
            class="inline-flex items-center gap-1 text-gray-500 hover:text-white text-sm transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            {{ $project->name }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            {{-- Task Header Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

                {{-- Inline badges --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[11px] px-2 py-0.5 rounded font-medium
                                {{ $task->status->value === 'todo' ? 'bg-gray-800 text-gray-400' : '' }}
                                {{ $task->status->value === 'in_progress' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                {{ $task->status->value === 'done' ? 'bg-lime-500/10 text-lime-400' : '' }}
                            ">
                        {{ $task->status->value === 'in_progress' ? 'In Progress' : ucfirst($task->status->value) }}
                    </span>

                    <span class="text-[11px] px-2 py-0.5 rounded font-medium
                                {{ $task->priority->value === 'high' ? 'bg-red-500/10 text-red-400' : '' }}
                                {{ $task->priority->value === 'medium' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                {{ $task->priority->value === 'low' ? 'bg-gray-800 text-gray-500' : '' }}
                            ">
                        {{ ucfirst($task->priority->value) }}
                    </span>

                    @if($task->due_date)
                        <span
                            class="text-[11px] px-2 py-0.5 rounded font-medium
                                            {{ $task->due_date->isPast() && $task->status->value !== 'done' ? 'bg-red-500/10 text-red-400' : 'bg-gray-800 text-gray-500' }}">
                            <i data-lucide="calendar" class="w-3 h-3 inline -mt-0.5"></i>
                            {{ $task->due_date->format('M d') }}
                        </span>
                    @endif

                    @if($task->assignee)
                        <div class="flex items-center gap-1.5 ml-auto">
                            <div
                                class="w-5 h-5 rounded-full bg-gray-800 flex items-center justify-center text-[10px] font-medium text-gray-400">
                                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-500 text-[11px]">{{ $task->assignee->name }}</span>
                        </div>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-lg font-semibold text-white leading-snug">{{ $task->title }}</h1>

                {{-- Description --}}
                @if($task->description)
                    <p class="text-gray-400 text-sm mt-3 leading-relaxed">{{ $task->description }}</p>
                @else
                    <p class="text-gray-700 text-xs mt-3 italic">No description provided</p>
                @endif
            </div>

            {{-- Comments --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl">
                <div class="px-5 py-4 border-b border-gray-800">
                    <h2 class="text-white text-sm font-semibold">Comments</h2>
                </div>

                <div class="p-5">
                    {{-- Comment list --}}
                    @if($comments->isNotEmpty())
                        <div class="space-y-4 mb-5">
                            @foreach($comments as $comment)
                                <div class="flex gap-3">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-800 flex items-center justify-center text-[10px] font-medium text-gray-400 shrink-0 mt-0.5">
                                        {{ strtoupper(substr($comment->author->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-white text-xs font-medium">{{ $comment->author->name }}</span>
                                            <span
                                                class="text-gray-700 text-[11px]">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-400 text-sm mt-1 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-700 text-xs mb-5">No comments yet</p>
                    @endif

                    {{-- Add comment --}}
                    <form action="{{ route('tasks.comments.store', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                        method="POST">
                        @csrf
                        <textarea name="body" rows="3" placeholder="Write a comment..."
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500 resize-none mb-2"></textarea>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-3.5 py-1.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-xs">
                                Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="space-y-4">

            {{-- Properties card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl">
                <div class="px-5 py-4 border-b border-gray-800">
                    <h2 class="text-white text-sm font-semibold">Properties</h2>
                </div>

                <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}" method="POST"
                    class="p-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                            <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>Todo</option>
                            <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="done" {{ $task->status->value === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Priority</label>
                        <select name="priority"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                            <option value="low" {{ $task->priority->value === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority->value === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $task->priority->value === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Assignee</label>
                        <select name="assigned_to"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                            <option value="">Unassigned</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Due Date</label>
                        <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-xs">
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- Info card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Created by</span>
                        <span class="text-gray-300">{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="text-gray-300">{{ $task->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($task->updated_at->ne($task->created_at))
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Updated</span>
                            <span class="text-gray-300">{{ $task->updated_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Danger zone --}}
            <form action="{{ route('tasks.destroy', [$currentWorkspace->slug, $project->id, $task->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this task?')"
                    class="w-full py-2 text-red-500 hover:text-red-400 hover:bg-red-500/5 text-xs rounded-lg transition-colors">
                    Delete Task
                </button>
            </form>
        </div>
    </div>

@endsection