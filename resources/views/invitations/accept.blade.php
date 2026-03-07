<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation - TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-gray-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-8 py-10 bg-gray-900 rounded-2xl border border-gray-800">
        <div class="m-8">
            <h1 class="text-2xl font-bold text-white">You are invited</h1>
            <p class="text-gray-500 text-sm mt-1">
                {{ $invitation->inviter->name }} invited you to join
                <span class="text-white font-medium">
                    {{ $invitation->workspace->name }}
                </span>
                as <span class="text-lime-400">{{ $invitation->role->value }}</span>
            </p>
        </div>

        <form action="{{ route('invitations.accept', $invitation->token) }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors">
                Accept Invitation
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            This Invitation expired at
            <span class="text-gray-400">
                {{ $invitation->expires_at->format('d M Y') }}
            </span>
        </p>
    </div>
</body>

</html>