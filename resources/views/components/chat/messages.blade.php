{{-- Chat Messages Component --}}
<div class="space-y-1">
    <template x-for="msg in chatMessages" :key="msg.id">
        <div class="flex gap-3 group hover:bg-white/[.03] px-2 py-1.5 -mx-2 rounded-xl transition-colors msg-bubble-enter"
             :class="msg.user.id === currentUserId ? 'flex-row-reverse' : ''">

            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shrink-0 text-white font-bold text-xs border border-gray-800"
                 x-text="msg.user.name.charAt(0).toUpperCase()"></div>

            <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex items-baseline gap-2 mb-0.5"
                     :class="msg.user.id === currentUserId ? 'flex-row-reverse' : ''">
                    <span class="text-white font-semibold text-[13px]"
                          x-text="msg.user.id === currentUserId ? 'You' : msg.user.name"></span>
                    <span class="text-gray-500 text-[11px]" x-text="msg.time ?? 'Just now'"></span>
                </div>

                <p x-show="msg.body" class="text-gray-300 text-[13px] leading-relaxed break-words"
                   :class="msg.user.id === currentUserId ? 'text-right' : ''"
                   x-html="msg.body.replace(/\n/g, '<br>')"></p>

                <template x-if="msg.attachments && msg.attachments.length > 0">
                    <div class="mt-2 space-y-1"
                         :class="msg.user.id === currentUserId ? 'flex flex-col items-end' : ''">
                        <template x-for="att in msg.attachments" :key="att.id">
                            <div>
                                <a x-show="att.mime.startsWith('image/')" :href="att.url"
                                   target="_blank" class="block">
                                    <img :src="att.url" :alt="att.name"
                                         class="max-w-xs rounded-lg border border-gray-700 hover:border-indigo-500 transition-colors">
                                </a>

                                <a x-show="!att.mime.startsWith('image/')" :href="att.url" download
                                   class="flex items-center gap-2 bg-gray-800 hover:bg-gray-750 rounded-lg p-2 max-w-xs transition-colors group">
                                    <div class="w-8 h-8 rounded bg-gray-700 flex items-center justify-center shrink-0">
                                        <i data-lucide="file" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-white truncate" x-text="att.name"></p>
                                        <p class="text-[10px] text-gray-500"
                                           x-text="(att.size / 1024).toFixed(1) + ' KB'"></p>
                                    </div>
                                    <i data-lucide="download"
                                       class="w-4 h-4 text-gray-500 group-hover:text-indigo-400 shrink-0"></i>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>

    {{-- Empty State --}}
    <div x-show="!isLoading && chatMessages.length === 0"
         class="py-16 flex flex-col items-center gap-1 text-center">
        <i data-lucide="messages-square" class="w-12 h-12 mb-3 text-gray-500"></i>
        <p class="text-gray-300 font-semibold text-sm" x-text="emptyTitle || 'No messages yet'"></p>
        <p class="text-gray-500 text-xs" x-text="emptySubtitle || 'Start the conversation.'"></p>
    </div>

    {{-- Loading Skeleton --}}
    <div x-show="isLoading" class="space-y-3 p-4">
        <div class="flex gap-3 animate-pulse">
            <div class="w-8 h-8 rounded-full bg-gray-800"></div>
            <div class="flex-1 space-y-2">
                <div class="h-3 bg-gray-800 rounded w-1/4"></div>
                <div class="h-3 bg-gray-800 rounded w-3/4"></div>
            </div>
        </div>
        <div class="flex gap-3 animate-pulse">
            <div class="w-8 h-8 rounded-full bg-gray-800"></div>
            <div class="flex-1 space-y-2">
                <div class="h-3 bg-gray-800 rounded w-1/4"></div>
                <div class="h-3 bg-gray-800 rounded w-2/3"></div>
            </div>
        </div>
    </div>
</div>
