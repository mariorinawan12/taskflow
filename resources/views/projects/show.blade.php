@extends('layouts.app')

@section('title', $project->name)

@section('content')

<div x-data="{ activeTab: 'overview', ...projectChatComponent({{ $project->id }}, {{ auth()->id() }})}">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $project->name }}</h1>
            @if($project->description)
                <p class="text-gray-400 text-sm mt-1">{{ $project->description }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tasks.create', [$currentWorkspace->slug, $project->id]) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                New Task
            </a>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex items-center gap-1 mb-6 border-b border-gray-800">
        <button @click="activeTab = 'overview'"
            :class="activeTab === 'overview' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-3 text-sm font-medium transition-colors flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-4 h-4"></i>
            Overview
        </button>
        <button @click="activeTab = 'discussion'"
            :class="activeTab === 'discussion' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-3 text-sm font-medium transition-colors flex items-center gap-2">
            <i data-lucide="message-square" class="w-4 h-4"></i>
            Discussion
        </button>
        <button @click="activeTab = 'calendar'"
            :class="activeTab === 'calendar' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
            class="px-4 py-3 text-sm font-medium transition-colors flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            Calendar
        </button>
    </div>

    {{-- Tab Content: Overview (Kanban Board) --}}
    <div x-show="activeTab === 'overview'" x-cloak>
        <x-kanban-board :tasks="$tasks" :project="$project" :workspace="$currentWorkspace" />
    </div>

    {{-- Tab Content: Discussion --}}
    <div x-show="activeTab === 'discussion'" x-cloak>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl flex flex-col overflow-hidden"
             style="height: calc(100vh - 250px); min-height: 500px;">
             
             {{-- Message Area --}}
             <div class="flex-1 overflow-y-auto p-4 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                <x-chat.messages />
             </div>

             <x-chat.input placeholder="Write a message about this project..." />
        </div>
    </div>

    <div x-show="activeTab === 'calendar'" x-cloak>
        <x-calendar :tasks="$tasks" :project="$project" :workspace="$currentWorkspace" />
    </div>

</div>

{{-- Project Chat Component Script --}}
<script>
    function projectChatComponent(projectId, currentUserId){
        return {
            chatMessages: [],
            body: '',
            selectedFiles: [],
            isLoading: true,
            isSending: false,
            currentUserId: currentUserId,

            init() {
                this.$watch('activeTab', value => {
                    if (value === 'discussion' && this.chatMessages.length === 0){
                        this.loadMessages();
                    }
                });
            },

            async loadMessages() {
                this.isLoading = true;
                try {
                    const response = await fetch(`/{{ $currentWorkspace->slug }}/chat/project/${projectId}/messages`);
                    const data = await response.json();

                    if (data.status === 'success') {
                        this.chatMessages = data.messages;
                    }
                } catch (error) {
                    console.error('Load messages error:', error);
                } finally {
                    this.isLoading = false;
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                }
            },

            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                this.selectedFiles = files.map(file => ({
                    file: file,
                    name: file.name,
                    preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
                }));
                this.$nextTick(() => {
                    lucide.createIcons();
                });
            },

            removeFile(index) {
                this.selectedFiles.splice(index, 1);
            },

            async sendMessage() {
                if (this.isSending || (!this.body.trim() && this.selectedFiles.length === 0)) return;

                this.isSending = true;
                const formData = new FormData();
                formData.append('body', this.body);

                this.selectedFiles.forEach((fileObj, index) => {
                    formData.append(`attachments[${index}]`, fileObj.file);
                });

                try{
                    const response = await fetch(`/{{ $currentWorkspace->slug }}/chat/project/${projectId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        this.body = '';
                        this.selectedFiles = [];
                        await this.loadMessages();
                    }
                } catch (error) {
                    console.error('Send message error:', error);
                } finally {
                    this.isSending = false;
                }
            }
        }
    }
</script>

@endsection