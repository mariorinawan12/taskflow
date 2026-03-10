<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspaces - Taskflow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } }
        }
    </script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-white">Your Workspaces</h1>
            <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->name }} . {{ auth()->user()->email }}</p>
        </div>

        @if($workspaces->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <p class="text-gray-400 text-sm">No workspaces yet</p>
                <p class="text-gray-600 text-xs mt-1 mb-4">Create your first workspace to get started</p>
                <a href="{{ route('workspace.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Create Workspace
                </a>
            </div>
        @else
            <div class="space-y-2 mb-4">
                @foreach($workspaces as $workspace)
                    <a href="{{ route('workspace.dashboard', $workspace->slug) }}"
                        class="flex items-center justify-between p-4 bg-gray-900 border border-gray-800 rounded-xl hover:border-gray-700 transition-all duration-150 group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center shrink-0">
                                <span
                                    class="text-white font-semibold text-sm">{{ strtoupper(substr($workspace->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h2 class="text-white text-sm font-semibold">{{ $workspace->name }}</h2>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] font-medium
                                                    {{ $workspace->pivot->role === 'owner' ? 'text-lime-400' : '' }}
                                                    {{ $workspace->pivot->role === 'admin' ? 'text-blue-400' : ''}}
                                                    {{ $workspace->pivot->role === 'member' ? 'text-gray-500' : '' }}">
                                        {{ ucfirst($workspace->pivot->role) }}
                                    </span>
                                    <span class="text-gray-700">.</span>
                                    <span class="text-gray-600 text-[11px]">{{ $workspace->projects_count }} projects</span>
                                    <span class="text-gray-700">.</span>
                                    <span class="text-gray-600 text-[11px]">{{ $workspace->members_count }} members</span>
                                </div>
                            </div>
                        </div>
                        <i data-lucide="chevron-right"
                            class="w-4 h-4 text-gray-700 group-hover:text-gray-500 transition-colors"></i>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('workspace.create') }}"
                class="flex items-center justify-center gap-2 w-full py-2.5 border border-dashed border-gray-800 hover:border-gray-700 text-gray-500 hover:text-white rounded-xl transition-colors text-sm">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                New Workspace
            </a>
        @endif

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-white text-xs transition-colors">
                Profile
            </a>
            <span class="text-gray-800">.</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-red-400 text-xs transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>