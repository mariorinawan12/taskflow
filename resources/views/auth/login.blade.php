<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">


    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Welcome</h1>
            <p class="text-gray-500 text-sm mt-1">Login to your workspace</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:border-lime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Password</label>
                <input type="password" name="password" placeholder="Enter your password"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:border-lime-500">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 accent-lime-500">
                <label for="remember" class="text-sm text-gray-400">
                    Remember me
                </label>
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors">
                Login
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Don't have account?
            <a href="{{ route('register') }}" class="text-lime-500 hover:underline">Register</a>
        </p>
    </div>


</body>

</html>