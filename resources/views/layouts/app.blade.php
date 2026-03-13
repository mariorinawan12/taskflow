<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskFlow') - {{ $currentWorkspace->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        aside::-webkit-scrollbar {
            width: 4px;
        }

        aside::-webkit-scrollbar-track {
            background: transparent;
        }

        aside::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }

        .nav-link {
            transition: all 0.15s ease;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex font-sans">
    {{-- SIDEBAR --}}
    <aside
        class="w-64 min-h-screen bg-gray-900/80 backdrop-blur-sm border-r border-gray-800/50 flex flex-col fixed left-0 top-0 z-30">
        <div class="px-5 py-4 border-b border-gray-800/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center shrink-0">
                        <span class="text-white font-semibold text-sm">
                            {{ strtoupper(substr($currentWorkspace->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-white font-semibold text-sm truncate">
                            {{ $currentWorkspace->name }}
                        </h2>
                        <p class="text-gray-600 text-[11px] truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('workspace.index') }}"
                    class="text-gray-500 hover:text-white transition-colors shrink-0 p-1.5 hover:bg-gray-800 rounded-lg"
                    title="Switch Workspace">
                    <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5"></i>
                </a>
            </div>

        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-3 mb-2 text-[11px] text-gray-600 uppercase tracking-wider font-medium">Menu</p>
            <a href="{{ route('workspace.dashboard', $currentWorkspace->slug) }}"
                class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('workspace.dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                Dashboard
            </a>

            <a href="{{ route('workspace.chat', $currentWorkspace->slug) }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('workspace.chat') ? 'bg-indigo-500/10 text-indigo-400 font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Messages</span>
            </a>

            <a href="{{ route('projects.index', $currentWorkspace->slug)}}"
                class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
            {{ request()->routeIs('projects.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                Projects
            </a>

            <a href="{{ route('workspace.members', $currentWorkspace->slug) }}"
                class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                    {{ request()->routeIs('workspace.members') ? 'bg-indigo-500/10 text-indigo-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                Members
            </a>

            <a href="{{ route('notifications.index', $currentWorkspace->slug) }}"
                class="nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('notifications.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                <span class="flex items-center gap-3">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                    Notifications
                </span>
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->whereRaw("(data::jsonb->>'workspace_id')::integer = ?", [session('current_workspace_id')])->count();

                @endphp
                @if($unreadCount > 0)
                    <span
                        class="px-2 py-0.5 bg-lime-500 text-black text-[11px] font-bold rounded-full min-w-[20px] text-center">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </nav>

        {{-- User Profile Section --}}
        <div class="px-3 py-4 border-t border-gray-800/50">
            <a href="{{ route('profile.edit') }}"
                class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 {{ request()->routeIs('profile.*') ? 'bg-gray-800' : 'hover:bg-gray-800/70' }}">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-xs font-bold text-black shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="nav-link w-full flex items-center gap-3 text-left px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/5 rounded-xl">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-8">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>