<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Taskflow</title>
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
            <h1 class="text-xl font-bold text-white">Profile</h1>
            <p class="text-gray-500 text-sm mt-1">Update your account information</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-lime-500/10 border border-lime-500/20 rounded-lg">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Account Info --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-4">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                </div>

                <div class="border-t border-gray-800 pt-4">
                    <p class="text-xs text-gray-600 mb-3">Leave empty if you don't want to change password</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">New Password</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-gray-500">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                    Save Changes
                </button>
            </form>
        </div>

        <a href="javascript:history.back()"
            class="block text-center text-gray-500 hover:text-white text-sm transition-colors">
            ← Back
        </a>
    </div>

</body>

</html>