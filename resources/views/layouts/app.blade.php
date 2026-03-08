<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskFlow') - {{ $currentWorkspace->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex">
    <aside class="w-60 min-h-screen bg-gray-900 border-r border-gray-800 flex flex-col fixed left-0 top-0">
        <div class="px-5 py-4 border-b border-gray-800">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Workspace</p>
            <h2 class="text-white font-semibold truncate">{{ $currentWorkspace->name }}</h2>
            <a href="{{ route('workspace.index') }}"
                class="text-xs text-gray-500 hover:text-lime-400 transition-colors mt-1 block">
                Switch Workspace →
            </a>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="{{ route('workspace.dashboard', $currentWorkspace->slug) }}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-colors {{ request()->routeIs('workspace.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                Dashboard
            </a>

            <a href="{{ route('projects.index', $currentWorkspace->slug)}}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-colors {{ request()->routeIs('projects.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                Projects
            </a>

            <a href="{{ route('workspace.members', $currentWorkspace->slug) }}"
                class="flex items-center gap-3 px-3 p-2 rounded-xl text-sm transition-colors {{ request()->routeIs('workspace.members') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                Members
            </a>

            <a href="{{ route('notifications.index', $currentWorkspace->slug) }}"
                class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-colors {{ request()->routeIs('notifications.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <span class="flex items-center gap-3">
                    Notifications
                </span>
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 bg-lime-500 text-black text-xs font-bold rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </nav>

        <div class="px-3 py-4 border-t border-gray-800">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-colors mb-2{{ request()->routeIs('profile.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <div>
                    <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-xs">{{ auth()->user()->email }}</p>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-800 rounded-xl transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="ml-60 flex-1 p-8">
        @yield('content')
    </main>
</body>

</html>