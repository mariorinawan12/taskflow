@extends('layouts.app')

@section('title', 'Members')

@section('content')


    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Members</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/10 border border-lime-500/20 rounded-xl">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8">
            <h2 class="text-white font-semibold mb-4">Invite Member</h2>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('workspace.members.invite', $currentWorkspace->slug) }}" method="POST"
                class="flex gap-3">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Input email"
                    class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:border-lime-500">
                <select name="role"
                    class="px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-lime-500">
                    <option value="member">Member</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="submit"
                    class="px-4 py-2.5 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-xl transition-colors">
                    Invite
                </button>
            </form>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-semibold mb-4">
                {{ $members->count() }} Members
            </h2>

            <div class="space-y-3">
                @foreach($members as $member)
                    <div class="flex items-center justify-between py-3 border-b border-gray-800 last:border-0">
                        <div>
                            <p class="text-white font-medium">{{ $member->name }}</p>
                            <p class="text-gray-500 text-sm">{{ $member->email }}</p>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $member->pivot->role === 'owner' ? 'bg-lime-500/10 text-lime-400' : 'bg-gray-800 text-gray-400' }}">
                            {{ $member->pivot->role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


@endsection