<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg px-8 py-10">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Workspaces</h1>
            <p class="text-gray-500 text-sm mt-1">Choose your Workspaces</p>
        </div>

        @if($workspaces->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <p class="text-gray-500 mb-4">You don't have any workspace</p>
                <a href="{{ route('workspace.create') }}"
                    class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors text-sm">
                    + Create Workspace
                </a>
            </div>
        @else
            <div class="space-y-3 mb-6">
                @foreach($workspaces as $workspace)
                    <a href="{{ route('workspace.dashboard', $workspace->slug) }}"
                        class="flex items-center justify-between p-5 bg-gray-900 border border-gray-800 rounded-2xl hover:border-gray-700 transition-colors">
                        <div>
                            <h2 class="text-white font-medium">{{ $workspace->name }}</h2>
                            <p class="text-gray-500 text-sm mt-0.5">{{ $workspace->pivot->role }}</p>
                        </div>
                        <span class="text-gray-600 text-sm">→</span>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('workspace.create') }}"
                class="block text-center px-4 py-2.5 border border-gray-800 hover:border-gray-700 text-gray-400 hover:text-white rounded-xl transition-colors text-sm">
                + Create New Workspace
            </a>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="mt-6 text-center">
            @csrf
            <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition-colors">
                Logout
            </button>
        </form>
    </div>

</body>

</html>