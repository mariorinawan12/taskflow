@extends('layouts.app')

@section('title', 'Messages')

@section('content')

    <style>
        #chat-root {
            margin: -2rem;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .msg-bubble-enter {
            animation: msgIn 0.18s ease-out both;
        }

        @keyframes msgIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .channel-pill,
        .dm-pill {
            transition: background .12s, color .12s
        }

        .channel-pill.active {
            background: rgba(99, 102, 241, .15);
            color: #a5b4fc;
        }

        .channel-pill:not(.active):hover {
            background: rgba(255, 255, 255, .04);
            color: #e5e7eb;
        }

        .dm-pill.active {
            background: rgba(255, 255, 255, .06);
            color: #f9fafb;
        }

        .dm-pill:not(.active):hover {
            background: rgba(255, 255, 255, .04);
            color: #e5e7eb;
        }

        textarea {
            field-sizing: content;
        }

        /* New DM button subtle pulse on hover */
        .new-dm-btn:hover {
            background: rgba(99, 102, 241, .2);
            color: #a5b4fc;
        }

        .new-dm-btn {
            transition: background 0.15s, color 0.15s;
        }
    </style>

    <div id="chat-root" x-data="chatComponent('{{ $workspace->id }}')">

        {{-- Main layout --}}

        {{-- ── Sidebar ── --}}
        <aside class="w-[240px] shrink-0 bg-gray-900 border border-gray-800  flex flex-col overflow-hidden">
            <div class="flex-1 overflow-y-auto scrollbar-none p-3 space-y-5">

                {{-- Channels --}}
                <section>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest px-2 mb-2">Channels</p>
                    <button @click="switchChannel('workspace', 'general', 'General Workspace')"
                        :class="activeChat === 'general' ? 'active' : ''"
                        class="channel-pill w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-gray-400 text-sm font-medium transition-colors">
                        <i data-lucide="hash" class="w-4 h-4 shrink-0"></i>
                        General Workspace
                    </button>
                </section>

                {{-- Direct Messages --}}
                <section>
                    {{-- Section header with inline + button --}}
                    <div class="flex items-center justify-between px-2 mb-2">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Direct Messages</p>
                        <button @click="isModalOpen = true"
                            class="new-dm-btn w-5 h-5 rounded-md flex items-center justify-center text-gray-500">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    @forelse($conversations as $conv)
                        @php $label = $conv->display_name ?? 'Direct Message'; @endphp
                        <button @click="switchChannel('conversation', {{ $conv->id }}, '{{ $label }}')"
                            :class="activeChat === {{ $conv->id }} ? 'active' : ''"
                            class="dm-pill w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-gray-400 transition-colors">
                            <span
                                class="w-7 h-7 rounded-full bg-gray-700 border border-gray-600 flex items-center justify-center text-xs font-bold text-white shrink-0">
                                {{ strtoupper(substr($label, 0, 1)) }}
                            </span>
                            <span class="text-[13px] font-medium truncate">{{ $label }}</span>
                        </button>
                    @empty
                        <p class="text-xs text-gray-600 px-2.5 italic">No direct messages yet.</p>
                    @endforelse
                </section>

            </div>
        </aside>

        {{-- ── Chat panel ── --}}
        <div class="flex-1 min-w-0 bg-gray-900 border border-gray-800  flex flex-col overflow-hidden">

            {{-- Top bar --}}
            <header class="h-14 border-b border-gray-800 flex items-center justify-between px-5 shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                        <i data-lucide="hash" class="w-4 h-4 text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-[14px] leading-none" x-text="activeName"></p>
                        <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            <span
                                x-text="activeType === 'workspace' ? '{{ $members->count() + 1 }} members' : activeName"></span>
                        </p>
                    </div>
                </div>

                <button @click="showAttachments = true; fetchAttachments()"
                    class="p-2 text-gray-500 hover:text-indigo-400 hover:bg-gray-800 rounded-lg transition-colors">
                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                </button>
            </header>


            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto scrollbar-none px-5 py-4 space-y-1" id="chat-message-container">

                {{-- Workspace messages --}}
                <div x-show="activeType === 'workspace'" x-cloak>
                    <x-chat.messages />
                </div>

                {{-- DM messages --}}
                <div x-show="activeType === 'conversation'" x-cloak>
                    <x-chat.messages />
                </div>

            </div>


            {{-- Input Area --}}
            <x-chat.input />

        </div>



        {{-- Modal --}}
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click="isModalOpen = false"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70" x-cloak>
            <div @click.stop
                class="w-full max-w-md bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden msg-bubble-enter">
                <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-white font-bold tracking-tight">New Message</h3>
                        <p class="text-xs text-gray-500 mt-0.5"
                            x-text="selectedMembers.length === 0 ? 'Select members' : selectedMembers.length === 1 ? 'Direct message' : `Group chat (${selectedMembers.length} members)`">
                        </p>
                    </div>
                    <button @click="isModalOpen = false" class="text-gray-500 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Selected members chips -->
                <div x-show="selectedMembers.length > 0" class="px-4 pt-3 pb-2 border-b border-gray-800">
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="member in selectedMembers" :key="member.id">
                            <div
                                class="flex items-center gap-1.5 bg-indigo-500/20 text-indigo-300 px-2 py-1 rounded-lg text-xs">
                                <span x-text="member.name"></span>
                                <button @click="toggleMember(member)" class="hover:text-white">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Group name input (only show if multiple selected) -->
                <div x-show="selectedMembers.length > 1" class="px-4 pt-3 pb-2 border-b border-gray-800">
                    <input type="text" x-model="groupName" placeholder="Group name (optional)"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div class="max-h-80 overflow-y-auto p-2 scrollbar-none">
                    @foreach($members as $member)
                        <button @click="toggleMember({id: {{ $member->id }}, name: '{{ $member->name }}'})"
                            :class="selectedMembers.find(m => m.id === {{ $member->id }}) ? 'bg-indigo-500/10 border-indigo-500/50' : 'hover:bg-white/[.04] border-transparent'"
                            class="w-full flex items-center gap-3 p-3 rounded-xl transition-all group text-left border">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center text-white font-bold border border-gray-700 group-hover:border-indigo-500/50 transition-colors">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-white group-hover:text-indigo-300 transition-colors">
                                    {{ $member->name }}
                                </p>
                                <p class="text-[11px] text-gray-500">Member of {{ $workspace->name }}</p>
                            </div>
                            <div x-show="selectedMembers.find(m => m.id === {{ $member->id }})" class="text-indigo-400">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Footer with action button -->
                <div class="p-4 border-t border-gray-800">
                    <button @click="createConversation()" :disabled="selectedMembers.length === 0"
                        :class="selectedMembers.length > 0 ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-gray-700 text-gray-500 cursor-not-allowed'"
                        class="w-full py-2.5 rounded-lg font-semibold text-sm transition-colors">
                        <span
                            x-text="selectedMembers.length === 0 ? 'Select members' : selectedMembers.length === 1 ? 'Start chat' : 'Create group'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Attachments Modal --}}
        <x-chat.attachments />



        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('chatComponent', (workspaceId) => ({
                    activeChat: 'general',
                    activeType: 'workspace',
                    activeName: 'General Workspace',
                    isModalOpen: false,
                    workspaceId,
                    workspaceSlug: '{{ $workspace->slug }}',
                    currentUserId: {{ auth()->id() }},
                    body: '',
                    isSubmitting: false,
                    chatMessages: [],
                    _poll: null,
                    selectedFiles: [],
                    selectedMembers: [],
                    groupName: '',
                    isLoading: false,
                    showAttachments: false,
                    attachmentsList: [],
                    isLoadingAttachments: false,

                    init() {
                        this.fetchMessages();
                        this.$nextTick(() => this.scrollToBottom());
                        this._poll = setInterval(() => {
                            if (this.activeType === 'conversation') this.fetchMessages();
                        }, 3000);
                    },

                    async switchChannel(type, id, name) {
                        this.activeType = type;
                        this.activeChat = id;
                        this.activeName = name;
                        this.chatMessages = [];
                        await this.fetchMessages();
                    },

                    async fetchMessages() {
                        this.isLoading = true;
                        try {
                            const endpoint = this.activeType === 'workspace'
                                ? `/${this.workspaceSlug}/chat/workspace/${this.workspaceId}/messages`
                                : `/${this.workspaceSlug}/chat/conversations/${this.activeChat}/messages`;

                            const res = await fetch(endpoint);
                            const data = await res.json();
                            console.log('Fetched Messages:', data);
                            if (data.status === 'success') {
                                this.chatMessages = data.messages;
                                console.log('chatMessages after fetch:', this.chatMessages);
                                this.$nextTick(() => {
                                    this.scrollToBottom();
                                    lucide.createIcons();
                                });
                            }
                        } catch { }
                        finally {
                            this.isLoading = false;
                        }
                    },

                    scrollToBottom() {
                        const el = document.getElementById('chat-message-container');
                        if (el) el.scrollTop = el.scrollHeight;
                    },

                    async sendMessage() {
                        if ((!this.body.trim() && this.selectedFiles.length === 0) || this.isSubmitting) return;
                        this.isSubmitting = true;
                        try {
                            const endpoint = this.activeType === 'workspace'
                                ? `/${this.workspaceSlug}/chat/workspace/${this.workspaceId}`
                                : `/${this.workspaceSlug}/chat/conversation/${this.activeChat}`;

                            const formData = new FormData();
                            formData.append('body', this.body || '');

                            console.log('selectedFiles before append:', this.selectedFiles);
                            this.selectedFiles.forEach((fileObj, index) => {
                                console.log(`Appending file ${index}:`, fileObj.file);
                                formData.append(`attachments[${index}]`, fileObj.file);
                            });

                            // Debug: log FormData contents
                            let debugInfo = `Body: ${this.body || '(empty)'}\nFiles: ${this.selectedFiles.length}\n`;
                            for (let pair of formData.entries()) {
                                debugInfo += `${pair[0]}: ${pair[1] instanceof File ? pair[1].name : pair[1]}\n`;
                            }
                            console.log('FormData contents:', debugInfo);

                            // Uncomment to see alert before sending
                            // alert(debugInfo);

                            const res = await fetch(endpoint, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: formData,
                            });

                            // Debug: log response
                            console.log('Response status:', res.status);
                            const text = await res.text();
                            console.log('Response text:', text);

                            let result;
                            try {
                                result = JSON.parse(text);
                            } catch (e) {
                                console.error('Failed to parse JSON:', text);
                                alert('Server error. Check console for details.');
                                return;
                            }

                            if (result.status === 'success') {
                                this.body = '';
                                this.selectedFiles = [];
                                this.$refs.fileInput.value = '';
                                await this.fetchMessages();
                            } else {
                                console.error('Server returned error:', result);
                                alert(result.message || 'Failed to send message');
                            }
                        } catch (err) {
                            console.error('Send error', err);
                            alert('Failed to send message. Check console for details.');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },

                    toggleMember(member) {
                        const index = this.selectedMembers.findIndex(m => m.id === member.id);
                        if (index > -1) {
                            this.selectedMembers.splice(index, 1);
                        } else {
                            this.selectedMembers.push(member);
                        }
                    },

                    async createConversation() {
                        if (this.selectedMembers.length === 0) return;

                        try {
                            const userIds = this.selectedMembers.map(m => m.id);
                            const isDM = userIds.length === 1;

                            const payload = {
                                user_ids: userIds,
                                name: isDM ? null : (this.groupName || null)
                            };

                            const res = await fetch(`/${this.workspaceSlug}/chat/conversation`, {
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                            const data = await res.json();

                            if (data.status === 'success') {
                                this.isModalOpen = false;

                                const displayName = isDM ? this.selectedMembers[0].name : (this.groupName || `Group (${userIds.length} members)`);

                                this.selectedMembers = [];
                                this.groupName = '';

                                await this.switchChannel('conversation', data.conversation_id, displayName);
                            }
                        } catch (err) {
                            console.error('Failed to create conversation', err);
                            alert('Failed to create conversation');
                        }
                    },


                    handleFileSelect(event) {
                        const files = Array.from(event.target.files);
                        console.log('Files selected:', files);
                        this.selectedFiles = files.map(file => ({
                            file: file,
                            name: file.name,
                            size: this.formatFileSize(file.size),
                            preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
                        }));
                        console.log('selectedFiles array:', this.selectedFiles);
                        this.$nextTick(() => {
                            lucide.createIcons();
                        });
                    },

                    formatFileSize(bytes) {
                        if (bytes === 0) return '0 Bytes';
                        const k = 1024;
                        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                    },

                    removeFile(index) {
                        this.selectedFiles.splice(index, 1);
                        if (this.selectedFiles.length === 0) {
                            this.$refs.fileInput.value = '';
                        }
                    },

                    async fetchAttachments() {
                        this.isLoadingAttachments = true;
                        try {
                            const endpoint = this.activeType === 'workspace'
                             ? `/${this.workspaceSlug}/chat/workspace/${this.workspaceId}/attachments`
                             : `/${this.workspaceSlug}/chat/conversations/${this.activeChat}/attachments`;

                             const res = await fetch(endpoint);
                             const data = await res.json();

                             if (data.status === 'success'){
                                this.attachmentsList = data.attachments;
                             }
                        } catch (err) {
                            console.error('Failed to fetch attachments', err);
                        } finally {
                            this.isLoadingAttachments = false;
                        }
                    },
                }));
            });
        </script>
    </div>
@endsection