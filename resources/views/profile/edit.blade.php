<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Profile</h1>
            <p class="text-gray-500 text-sm mt-1">Update your account information</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/10 border border-lime-500/20 rounded-xl">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <hr class="border-gray-800">

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">
                    Old Password
                    <span class="text-gray-600">(fill this if you want to change password)</span>
                </label>
                <input type="password" name="current_password" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">New Password</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Rewrite New Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700
                           rounded-xl text-white focus:outline-none focus:border-lime-500">
            </div>

            <button type="submit" class="w-full py-2.5 bg-lime-500 hover:bg-lime-400
                       text-black font-semibold rounded-xl transition-colors">
                Save
            </button>


        </form>

        <div class="mt-6 text-center">
            <a href="javascript:history.back()" class="text-gray-500 hover:text-white text-sm transition-colors">←
                Back</a>
        </div>

    </div>
</body>

</html>