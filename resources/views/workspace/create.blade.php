<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Workspace — TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Buat Workspace</h1>
            <p class="text-gray-500 text-sm mt-1">
                Workspace adalah tempat lo dan tim ngatur semua project.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('workspace.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">
                    Nama Workspace
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Contoh: Startup Gue, Agency Kreatif" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white placeholder-gray-600
                           focus:outline-none focus:border-lime-500">
            </div>

            <button type="submit" class="w-full py-2.5 bg-lime-500 hover:bg-lime-400
                       text-black font-semibold rounded-xl transition-colors">
                Buat Workspace
            </button>

        </form>

    </div>

</body>

</html>