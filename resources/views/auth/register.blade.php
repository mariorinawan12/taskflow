<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Taskflow</title>
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
            <h1 class="text-xl font-bold text-white">Create Account</h1>
            <p class="text-gray-500 text-sm mt-1">Start organizing projects with your team</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" autofocus
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="Min. 8 characters"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Retype your password"
                        class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                    Create Account
                </button>
            </form>
        </div>

        <p class="text-center text-gray-600 text-xs mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Sign in</a>
        </p>
    </div>

</body>

</html>