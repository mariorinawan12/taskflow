@extends('layouts.app')

@section('title', 'Members')

@section('content')

    <div>
        <div class="flex items-center mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">Members</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $currentWorkspace->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-lime-500/10 border border-lime-500/20 rounded-xl">
                <p class="text-lime-400 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Invite Section --}}
        @if($currentUserRole !== 'member')
            <div class="bg-gray-900 border border-gray-800 rounded-2xl mb-6">
                <div class="px-5 py-4 border-b border-gray-800">
                    <h2 class="text-white text-sm font-semibold">Invite Member</h2>
                </div>
                <div class="p-5">
                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                            @foreach($errors->all() as $error)
                                <p class="text-red-400 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('workspace.members.invite', $currentWorkspace->slug) }}" method="POST"
                        class="flex gap-2">
                        @csrf
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address"
                            class="flex-1 px-3.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-lime-500">
                        <select name="role"
                            class="px-3.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-lime-500">
                            <option value="member">Member</option>
                            @if($currentUserRole === 'owner')
                                <option value="admin">Admin</option>
                            @endif
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-lime-500 hover:bg-lime-400 text-black font-semibold rounded-lg transition-colors text-sm">
                            Invite
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Members List --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-800">
                <h2 class="text-white text-sm font-semibold">{{ $members->count() }} Members</h2>
            </div>

            <div class="divide-y divide-gray-800">
                @foreach($members as $member)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-xs font-medium text-gray-400 shrink-0">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-white text-sm font-medium">{{ $member->name }}</p>
                                <p class="text-gray-600 text-xs">{{ $member->email }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium
                                    {{ $member->pivot->role === 'owner' ? 'bg-lime-500/10 text-lime-400' : '' }}
                                    {{ $member->pivot->role === 'admin' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                    {{ $member->pivot->role === 'member' ? 'bg-gray-800 text-gray-500' : ''}}
                                    ">
                            {{ ucfirst($member->pivot->role) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection