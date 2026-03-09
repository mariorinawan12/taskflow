@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">Notifications</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="bell-off" class="w-5 h-5 text-gray-600"></i>
                </div>
                <p class="text-gray-400 text-sm">No notifications yet</p>
                <p class="text-gray-600 text-xs mt-1">You'll be notified when something happens</p>
            </div>
        @else
            <div class="bg-gray-900 border border-gray-800 rounded-2xl divide-y divide-gray-800">
                @foreach($notifications as $notification)
                    <div class="flex items-start gap-3 px-5 py-4 {{ $notification->read_at ? '' : 'bg-lime-500/[0.02]' }}">
                        @if($notification->type === 'App\Notifications\TaskAssigned')
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="user-plus" class="w-4 h-4 text-blue-400"></i>
                            </div>
                        @elseif($notification->type === 'App\Notifications\CommentAdded')
                            <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="message-circle" class="w-4 h-4 text-yellow-400"></i>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            @if($notification->type === 'App\Notifications\TaskAssigned')
                                <p class="text-sm text-gray-300">
                                    <span class="text-white font-medium">{{ $notification->data['assigned_by'] }}</span>
                                    assigned you to
                                    <a href="{{ route('tasks.show', [$currentWorkspace->slug, $notification->data['project_id'], $notification->data['task_id']]) }}"
                                        class="text-lime-400 hover:underline">
                                        {{ $notification->data['task_title'] }}
                                    </a>
                                </p>
                                <p class="text-gray-600 text-xs mt-1">{{ $notification->data['project_name'] ?? '' }}</p>
                            @elseif($notification->type === 'App\Notifications\CommentAdded')
                                <p class="text-sm text-gray-300">
                                    <span class="text-white font-medium">{{ $notification->data['comment_by'] }}</span>
                                    commented on
                                    <a href="{{ route('tasks.show', [$currentWorkspace->slug, $notification->data['project_id'], $notification->data['task_id']]) }}"
                                        class="text-lime-400 hover:underline">
                                        "{{ $notification->data['task_title'] }}"
                                    </a>
                                </p>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ Str::limit($notification->data['comment_body'], 80) }}
                                </p>
                            @endif
                            <p class="text-gray-700 text-[11px] mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$notification->read_at)
                                <span class="w-2 h-2 rounded-full bg-lime-500"></span>
                            @endif
                            <form action="{{ route('notifications.destroy', [$currentWorkspace->slug, $notification->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-gray-700 hover:text-red-400 transition-colors p-1 rounded hover:bg-gray-800">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

@endsection