@extends('layouts.app')

@section('title', $project->name)

@section('content')

<div x-data="{ activeTab: 'overview', ...projectChatComponent({{ $project->id }}, {{ auth()->id() }})}">

    {{-- Header --}}
    <div class="flex items-center gap-3 text-sm mb-4">
        <a href="{{ route('projects.index', $currentWorkspace->slug) }}"
           class="text-gray-500 hover:text-white transition-colors flex items-center gap-1.5 font-medium">
           <i data-lucide="arrow-left" class="w-4 h-4"></i>
           Projects
        </a>
        <span class="text-gray-700">/</span>
        <span class="text-gray-400 font-medium">{{ $project->name }}</span>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex items-center justify-between border-b border-gray-800 mb-6">
        <div class="flex items-center gap-1">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    Overview
            </button>
            <button @click="activeTab = 'discussion'"
                    :class="activeTab === 'discussion' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    Discussion
            </button>
            <button @click="activeTab = 'calendar'"
                    :class="activeTab === 'calendar' ? 'border-b-2 border-indigo-500 text-white' : 'text-gray-500 hover:text-gray-300'"
                    class="px-3 py-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Calendar
            </button>
        </div>
        <a href="{{ route('tasks.create', [$currentWorkspace->slug, $project->id]) }}"
            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 mb-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New Task
        </a>
    </div>

    {{-- Tab Content: Overview (Kanban Board) --}}
<div x-show="activeTab === 'overview'" x-cloak>
    <x-kanban-board :tasks="$tasks" :project="$project" :workspace="$currentWorkspace" />
</div>

{{-- Tab Content: Discussion --}}
<div x-show="activeTab === 'discussion'" x-cloak>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl flex flex-col overflow-hidden"
         style="height: calc(100vh - 150px); min-height: 500px;">
         
         {{-- Header --}}
         <header class="h-14 border-b border-gray-800 flex items-center justify-between px-5 shrink-0">
             <div class="flex items-center gap-3">
                 <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                     <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-400"></i>
                 </div>
                 <div>
                     <p class="text-white font-semibold text-[14px] leading-none">{{ $project->name }}</p>
                     <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                         <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                         <span>Project Discussion</span>
                     </p>
                 </div>
             </div>
             
             <button @click="showAttachments = true; fetchAttachments()" 
                     class="p-2 text-gray-500 hover:text-indigo-400 hover:bg-gray-800 rounded-lg transition-colors">
                 <i data-lucide="paperclip" class="w-4 h-4"></i>
             </button>
         </header>
         
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

    {{-- Attachments Modal --}}
    <x-chat.attachments/>

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
            showAttachments: false,
            attachmentsList: [],
            isLoadingAttachments: false,

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
            },

            async fetchAttachments() {
                this.isLoadingAttachments = true;
                try {
                    const response = await fetch(`/{{ $currentWorkspace->slug }}/chat/project/${projectId}/attachments`);
                    const data = await response.json();

                    if (data.status === 'success') {
                        this.attachmentsList = data.attachments;
                    }
                } catch (error) {
                    console.error('Failed to fetch attachments', error);
                } finally {
                    this.isLoadingAttachments = false;
                }
            }
        }
    }
</script>

@endsection