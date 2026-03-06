<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $currentWorkspace->name }} - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray950 text-gray-200 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-3xl font-bold text-white mb-2">
            {{ $currentWorkspace->name }}
        </h1>
        <p class="test-gray-500 mb-8">Dashboard Workspace</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-500 rounded-lg text-white">
                Logout
            </button>
        </form>
    </div>

</body>

</html>