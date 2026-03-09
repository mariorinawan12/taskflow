<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Workspace - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } }
        }
    </script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-sm">

        <div class="mb-6">
            <h1 class="text-xl font-bold text-white">Create Workspace</h1>
            <p class="text-gray-500 text-sm mt-1">A workspace is where your team organizes projects</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <form action="{{ route('workspace.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Workspace Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Acme Inc, My Team"
                        autofocus
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                    Create Workspace
                </button>
            </form>
        </div>

        <a href="{{ route('workspace.index') }}"
            class="block text-center text-gray-500 hover:text-white text-sm mt-4 transition-colors">
            ← Back to workspaces
        </a>
    </div>

</body>

</html>