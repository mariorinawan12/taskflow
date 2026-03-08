@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

    <div class="max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Notifications</h1>
            <p class="text-gray-500 text-sm mt-1">All your notifications.</p>
        </div>

        @if($notifications->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <p class="text-gray-500">There is no notifications</p>
            </div>
        @else

            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div
                        class="flex items-start justify-between p-5 bg-gray-900 border border-gray-800 rounded-2xl {{ $notification->read_at ? '' : 'border-lime-500/20' }}">
                        <div>
                            @if($notification->type === 'App\Notifications\TaskAssigned')
                                <p class="text-white text-sm">
                                    <span class="font-medium">{{ $notification->data['assigned_by'] }}</span>
                                    assigned you to task
                                    <span class="text-lime-400">"{{ $notification->data['task_title'] }}"</span>
                                </p>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ $notification->data['project_name'] }}
                                </p>
                            @endif

                            @if($notification->type === 'App\Notifications\CommentAdded')
                                <p class="text-white text-sm">
                                    <span class="font-medium">{{ $notification->data['comment_by'] }}</span>
                                    commented in task
                                    <span class="text-lime-400">{{ $notification->data['task_title'] }}</span>
                                </p>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ Str::limit($notification->data['comment_body'], 80) }}
                                </p>
                            @endif

                            <p class="text-gray-600 text-xs mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <form action="{{ route('notifications.destroy', [$currentWorkspace->slug, $notification->id]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-600 hover:text-red-400 text-xs transition-colors">
                                ✕
                            </button>

                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

@endsection