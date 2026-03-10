@extends('layouts.app')

@section('title', $task->title)

@section('content')

    {{-- Alpine Component for whole page --}}
    <div x-data="{ showEditModal: false }">

        {{-- Breadcrumb & Top Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('projects.show', [$currentWorkspace->slug, $project->id]) }}"
                    class="text-gray-500 hover:text-white transition-colors flex items-center gap-1.5 font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    {{ $project->name }}
                </a>
                <span class="text-gray-700">/</span>
                <span class="text-gray-400 font-medium truncate max-w-[150px] sm:max-w-xs"
                    title="{{ $task->title }}">{{ $task->title }}</span>
            </div>

            <div class="flex items-center gap-3">

                <form action="{{ route('tasks.destroy', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                    method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-2 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors flex items-center gap-1.5 border border-transparent hover:border-red-500/20">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

            {{-- LEFT COLUMN (3/5 width): Task Content --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Compact Inline Properties Capsule --}}
                <div
                    class="bg-gray-900 border border-gray-800 rounded-xl px-4 sm:px-1 py-3 text-[11px] w-full mb-4 shadow-sm grid grid-cols-4 divide-x divide-gray-700/60">

                    {{-- Status --}}
                    <div class="flex flex-col gap-1 px-4 items-center relative" x-data="{ open: false }">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Status</span>
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="px-2 py-0.5 rounded-md font-medium cursor-pointer hover:ring-1 hover:ring-gray-700 transition-all focus:outline-none flex items-center gap-1 w-fit
                                                        {{ $task->status->value === 'todo' ? 'bg-gray-800 text-gray-300' : '' }}
                                                        {{ $task->status->value === 'in_progress' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : '' }}
                                                        {{ $task->status->value === 'done' ? 'bg-lime-500/20 text-lime-400 border border-lime-500/30' : '' }}">
                            <span>{{ $task->status->value === 'in_progress' ? 'In Progress' : ucfirst($task->status->value) }}</span>
                            <i data-lucide="chevron-down" class="w-2.5 h-2.5 opacity-50"></i>
                        </button>
                        <div x-show="open" style="display: none;"
                            class="absolute top-full left-0 mt-2 w-36 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden">
                            <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                                method="POST">
                                @csrf @method('PATCH')

                                <button type="submit" name="status" value="todo"
                                    class="w-full text-left px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700">Todo</button>
                                <button type="submit" name="status" value="in_progress"
                                    class="w-full text-left px-3 py-1.5 text-xs text-indigo-300 hover:bg-gray-700">In
                                    Progress</button>
                                <button type="submit" name="status" value="done"
                                    class="w-full text-left px-3 py-1.5 text-xs text-lime-400 hover:bg-gray-700">Done</button>
                            </form>
                        </div>
                    </div>

                    {{-- Priority --}}
                    <div class="flex flex-col gap-1 px-4 items-center relative" x-data="{ open: false }">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Priority</span>
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="px-2 py-0.5 rounded-md font-medium cursor-pointer hover:ring-1 hover:ring-gray-700 transition-all focus:outline-none flex items-center gap-1 w-fit
                                                        {{ $task->priority->value === 'high' ? 'bg-red-500/20 text-red-400' : '' }}
                                                        {{ $task->priority->value === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                                        {{ $task->priority->value === 'low' ? 'bg-gray-800 text-gray-400' : '' }}">
                            <span>{{ ucfirst($task->priority->value) }}</span>
                            <i data-lucide="chevron-down" class="w-2.5 h-2.5 opacity-50"></i>
                        </button>
                        <div x-show="open" style="display: none;"
                            class="absolute top-full left-0 mt-2 w-32 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden">
                            <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                                method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" name="priority" value="high"
                                    class="w-full text-left px-3 py-1.5 text-xs text-red-400 hover:bg-gray-700">High</button>
                                <button type="submit" name="priority" value="medium"
                                    class="w-full text-left px-3 py-1.5 text-xs text-yellow-400 hover:bg-gray-700">Medium</button>
                                <button type="submit" name="priority" value="low"
                                    class="w-full text-left px-3 py-1.5 text-xs text-gray-400 hover:bg-gray-700">Low</button>
                            </form>
                        </div>
                    </div>

                    {{-- Assignees --}}
                    <div class="flex flex-col gap-1 px-4 items-center relative" x-data="{ open: false }">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Assignees</span>
                        <button type="button" @click="open = !open"
                            class="flex items-center -space-x-1 cursor-pointer hover:opacity-80 transition-opacity rounded-md focus:outline-none w-fit">
                            @forelse($task->assignees->take(3) as $assignee)
                                <div class="w-5 h-5 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center text-[9px] font-bold border-2 border-gray-900 ring-1 ring-gray-600"
                                    style="z-index: {{ 10 - $loop->index }}" title="{{ $assignee->name }}">
                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                </div>
                            @empty
                                <span
                                    class="px-2 py-0.5 bg-gray-800 text-gray-500 rounded-md italic font-medium hover:ring-1 hover:ring-gray-700">Unassigned</span>
                            @endforelse
                            @if($task->assignees->count() > 3)
                                <div
                                    class="w-5 h-5 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center text-[9px] font-bold border-2 border-gray-900">
                                    +{{ $task->assignees->count() - 3 }}
                                </div>
                            @endif
                        </button>
                        <div x-show="open" style="display: none;" @click.outside="open = false"
                            class="absolute top-full left-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden">
                            <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                                method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="_update_assignees" value="1">
                                @php $assigneeIds = $task->assignees->pluck('id')->toArray(); @endphp
                                <div class="max-h-40 overflow-y-auto p-1.5">
                                    <label
                                        class="flex items-center gap-2 p-1.5 hover:bg-gray-700 rounded-md cursor-pointer transition-colors">
                                        <input type="checkbox" name="assignees[]" value="{{ auth()->id() }}"
                                            class="rounded border-gray-600 text-indigo-500 focus:ring-transparent bg-gray-900"
                                            {{ in_array(auth()->id(), $assigneeIds) ? 'checked' : '' }}>
                                        <span class="text-xs text-white">Assign to me</span>
                                    </label>
                                    <div class="h-px bg-gray-700 my-1 mx-1"></div>
                                    @foreach($members as $member)
                                        @if($member->id !== auth()->id())
                                            <label
                                                class="flex items-center gap-2 p-1.5 hover:bg-gray-700 rounded-md cursor-pointer transition-colors">
                                                <input type="checkbox" name="assignees[]" value="{{ $member->id }}"
                                                    class="rounded border-gray-600 text-indigo-500 focus:ring-transparent bg-gray-900"
                                                    {{ in_array($member->id, $assigneeIds) ? 'checked' : '' }}>
                                                <span class="text-xs text-gray-300">{{ $member->name }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="p-1.5 border-t border-gray-700 bg-gray-800/50">
                                    <button type="submit"
                                        class="w-full py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold rounded-md transition-colors shadow-sm">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Due Date --}}
                    <div class="flex flex-col gap-1 px-4 items-center relative" x-data="{ open: false }">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Due</span>
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="px-2 py-0.5 rounded-md font-medium cursor-pointer hover:ring-1 hover:ring-gray-700 transition-all focus:outline-none flex items-center gap-1 w-fit
                                                        {{ $task->due_date && $task->due_date->isPast() && $task->status->value !== 'done' ? 'bg-red-500/20 text-red-400' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-300' }}">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <span>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'Set Date' }}</span>
                        </button>
                        <div x-show="open" style="display: none;"
                            class="absolute top-full left-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-xl shadow-xl z-20 overflow-hidden p-2.5">
                            <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                                method="POST" class="flex flex-col gap-2">
                                @csrf @method('PATCH')
                                <input type="date" name="due_date"
                                    value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                    class="w-full px-2 py-1 bg-gray-900 border border-gray-700 rounded-md text-xs text-white focus:ring-indigo-500 focus:border-indigo-500 [color-scheme:dark]">
                                <button type="submit"
                                    class="w-full py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold rounded-md transition-colors shadow-sm">Save</button>
                            </form>
                        </div>
                    </div>

                </div>

                {{-- Title Box (Inline Editable) --}}
                <div x-data="{ editingTitle: false }">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Task Title</h3>
                        <button type="button" @click="editingTitle = !editingTitle"
                            class="text-gray-500 hover:text-white transition-colors text-xs flex items-center gap-1">
                            <i data-lucide="edit-2" class="w-3 h-3"></i>
                            <span x-text="editingTitle ? 'Cancel' : 'Edit'"></span>
                        </button>
                    </div>

                    {{-- View Mode --}}
                    <div x-show="!editingTitle"
                        class="bg-gray-900 border border-transparent hover:border-gray-800 rounded-2xl p-4 sm:p-5 group cursor-text transition-colors">
                        <h1
                            class="text-xl sm:text-2xl font-bold text-white leading-tight group-hover:text-indigo-100 transition-colors">
                            {{ $task->title }}
                        </h1>
                    </div>

                    {{-- Edit Mode --}}
                    <div x-show="editingTitle" style="display: none;"
                        class="bg-gray-900 border border-indigo-500/50 rounded-2xl p-4 sm:p-5 ring-1 ring-indigo-500/50">
                        <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            method="POST" class="flex flex-col gap-3">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="title" value="{{ $task->title }}" required autofocus
                                class="w-full bg-transparent text-xl sm:text-2xl font-bold text-white outline-none border-b border-gray-700 focus:border-indigo-500 pb-1 placeholder-gray-600">

                            <div class="flex justify-end gap-2 mt-2">
                                <button type="button" @click="editingTitle = false"
                                    class="px-3 py-1.5 text-xs font-medium text-gray-400 hover:text-white transition-colors">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">Save
                                    Title</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Description Box (Min height forced) --}}
                {{-- Description Box (Inline Editable, Min height forced) --}}
                <div x-data="{ editingDesc: false, descContent: {{ json_encode($task->description) }} }">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Description</h3>
                        <button type="button" @click="editingDesc = !editingDesc"
                            class="text-gray-500 hover:text-white transition-colors text-xs flex items-center gap-1">
                            <i data-lucide="edit-2" class="w-3 h-3"></i>
                            <span x-text="editingDesc ? 'Cancel' : 'Edit'"></span>
                        </button>
                    </div>

                    {{-- View Mode --}}
                    <div x-show="!editingDesc"
                        class="bg-gray-900 border border-transparent hover:border-gray-800 rounded-2xl p-5 max-h-[350px] flex flex-col group cursor-text transition-colors"
                        style="height: calc(100vh - 300px);">
                        <div
                            class="prose prose-invert max-w-none text-gray-300 flex-1 group-hover:text-gray-200 transition-colors">
                            @if($task->description)
                                <div class="text-[15px] leading-relaxed whitespace-pre-wrap">{{ $task->description }}</div>
                            @else
                                <div
                                    class="h-full flex flex-col items-center justify-center text-gray-600 opacity-60 group-hover:text-indigo-400 group-hover:opacity-100 transition-all">
                                    <i data-lucide="file-text" class="w-12 h-12 mb-3"></i>
                                    <p class="text-sm font-medium">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Edit Mode --}}
                    <div x-show="editingDesc" style="display: none;"
                        class="bg-gray-900 border border-indigo-500/50 rounded-2xl p-5 min-h-[400px] flex flex-col ring-1 ring-indigo-500/50"
                        style="height: calc(100vh - 300px);">
                        <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            method="POST" class="flex flex-col h-full flex-1">
                            @csrf
                            @method('PATCH')
                            <textarea name="description" x-model="descContent" rows="10"
                                placeholder="Add a more detailed description..."
                                class="w-full h-full flex-1 bg-transparent text-[15px] leading-relaxed text-gray-300 resize-none outline-none border-none focus:ring-0 placeholder-gray-600 mb-4"></textarea>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-800">
                                <button type="button"
                                    @click="editingDesc = false; descContent = {{ json_encode($task->description) }}"
                                    class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Cancel</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">Save
                                    Description</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Inline Editable Properties Bar (Compact, Top-level) --}}


                {{-- Title Box (Inline Editable) --}}

            </div>

            {{-- RIGHT COLUMN (2/5 width): Discussion --}}
            <div class="lg:col-span-2 flex flex-col h-full space-y-2">

                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest pl-1">Discussion</h3>

                <div class="bg-gray-900 border border-gray-800 rounded-2xl flex flex-col overflow-hidden shadow-sm"
                    style="height: calc(100vh - 160px); min-height: 500px;">

                    {{-- Feed / History --}}
                    <div
                        class="flex-1 overflow-y-auto p-5 space-y-6 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                        @forelse($comments as $comment)
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0 mt-0.5 border border-indigo-500/20">
                                    <span class="text-xs font-semibold text-indigo-400">
                                        {{ strtoupper(substr($comment->author->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 cursor-default">
                                        <span class="text-white text-sm font-medium">{{ $comment->author->name }}</span>
                                        <span
                                            class="text-gray-500 text-[11px]">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div
                                        class="text-gray-300 text-[14px] leading-relaxed bg-gray-800/50 rounded-2xl rounded-tl-none px-4 py-3 border border-gray-700/50">
                                        {{ $comment->body }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col justify-center items-center text-gray-500 text-sm opacity-60">
                                <i data-lucide="messages-square" class="w-12 h-12 mb-3"></i>
                                <p>No discussion yet.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Reply Input area --}}
                    <div class="p-4 bg-gray-800/30 border-t border-gray-800">
                        <form
                            action="{{ route('tasks.comments.store', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                            method="POST">
                            @csrf
                            <div
                                class="relative bg-gray-900 border border-gray-700 rounded-xl focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all p-1">
                                <textarea name="body" rows="2" placeholder="Write a message..."
                                    class="w-full px-3 py-2 bg-transparent text-sm text-white placeholder-gray-500 resize-none outline-none border-none focus:ring-0"></textarea>

                                <div class="flex justify-between items-center px-2 pb-1">
                                    <div class="flex items-center gap-1 text-gray-500">
                                        <button type="button" class="p-1.5 hover:bg-gray-800 hover:text-gray-300 rounded"><i
                                                data-lucide="paperclip" class="w-4 h-4"></i></button>
                                        <button type="button" class="p-1.5 hover:bg-gray-800 hover:text-gray-300 rounded"><i
                                                data-lucide="image" class="w-4 h-4"></i></button>
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-colors text-xs shadow-sm">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>

        {{-- MODAL OVERLAY: EDIT PROPERTIES --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="showEditModal" x-transition:enter="ease-out duration-100" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-75"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60"
                @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                {{-- Modal Panel --}}
                <div x-show="showEditModal" x-transition:enter="ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-gray-900 border border-gray-700 text-left shadow-2xl sm:my-8 w-full max-w-lg">

                    {{-- Modal Header --}}
                    <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white" id="modal-title">Edit Task Properties</h3>
                        <button type="button" @click="showEditModal = false"
                            class="text-gray-400 hover:text-white transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Modal Body: Form --}}
                    <form action="{{ route('tasks.update', [$currentWorkspace->slug, $project->id, $task->id]) }}"
                        method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="_update_assignees" value="1">

                        <div class="px-6 py-6 space-y-6">

                            {{-- Status & Priority Side by Side --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Status</label>
                                    <select name="status"
                                        class="w-full px-4 py-2 bg-gray-800 border-gray-700 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>Todo
                                        </option>
                                        <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="done" {{ $task->status->value === 'done' ? 'selected' : '' }}>Done
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Priority</label>
                                    <select name="priority"
                                        class="w-full px-4 py-2 bg-gray-800 border-gray-700 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="low" {{ $task->priority->value === 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="medium" {{ $task->priority->value === 'medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="high" {{ $task->priority->value === 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Assignees (Custom Dropdown) --}}
                            <div class="relative" x-data="{openAssignees: false}">
                                <label
                                    class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Assignees</label>
                                @php
                                    $assigneeIds = $task->assignees->pluck('id')->toArray();
                                @endphp

                                <button type="button" @click="openAssignees = !openAssignees"
                                    @click.away="openAssignees = false"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-xl text-sm text-left text-white focus:outline-none focus:border-indigo-500 flex justify-between items-center transition-colors">
                                    <span class="text-gray-300">
                                        @if(count($assigneeIds) === 0)
                                            Select assignees...
                                        @else
                                            {{ count($assigneeIds) }} selected
                                        @endif
                                    </span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                </button>

                                <div x-show="openAssignees" style="display: none;" @click.stop
                                    class="absolute z-10 w-full mt-2 bg-gray-800 border border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                    <div class="p-2 space-y-1">
                                        <label
                                            class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded-lg cursor-pointer transition-colors">
                                            <input type="checkbox" name="assignees[]" value="{{ auth()->id() }}"
                                                class="rounded border-gray-600 text-indigo-500 focus:ring-transparent bg-gray-900"
                                                {{ in_array(auth()->id(), $assigneeIds) ? 'checked' : '' }}>
                                            <span class="text-sm text-white">
                                                Assign to me ({{ auth()->user()->name }})
                                            </span>
                                        </label>
                                        <div class="h-px bg-gray-700 my-2 mx-1"></div>
                                        <div
                                            class="px-2 py-1 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">
                                            Members</div>

                                        @foreach($members as $member)
                                            @if($member->id !== auth()->id())
                                                <label
                                                    class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded-lg cursor-pointer transition-colors">
                                                    <input type="checkbox" name="assignees[]" value="{{ $member->id }}"
                                                        class="rounded border-gray-600 text-indigo-500 focus:ring-transparent bg-gray-900"
                                                        {{ in_array($member->id, $assigneeIds) ? 'checked' : '' }}>
                                                    <span class="text-sm text-gray-300">{{ $member->name }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Due Date --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Due
                                    Date</label>
                                <input type="date" name="due_date"
                                    value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 bg-gray-800 border-gray-700 rounded-xl text-sm text-white focus:ring-indigo-500 focus:border-indigo-500 [color-scheme:dark]">
                            </div>

                        </div>

                        {{-- Modal Footer --}}
                        <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-700 flex justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection