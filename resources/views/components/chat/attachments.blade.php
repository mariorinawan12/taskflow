{{-- Chat Attachments Modal Component --}}

<div x-show="showAttachments"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @click="showAttachments = false"
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70"
     x-cloak>
    
     <div @click.stop
          class="w-full max-w-4xl bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden">
          
          <div class="p-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h3 class="text-white font-bold tracking-tight">Media & Files</h3>
                <p class="text-xs text-gray-500 mt-0.5" x-text="`${attachmentsList.length} files shared`"></p>
            </div>
            <button @click="showAttachments = false"
                    class="text-gray-500 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>

          {{-- Content --}}
          <div class="max-h-96 overflow-y-auto p-4">
            {{-- loading state --}}
            <div x-show="isLoadingAttachments" class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
            </div>

            {{-- empty state --}}
            <div x-show="!isLoadingAttachments && attachmentsList.length === 0"
                 class="flex flex-col items-center justify-center py-12 text-center">
                <i data-lucide="file-x" class="w-12 h-12 text-gray-500 mb-3"></i>
                <p class="text-gray-300 font-semibold">No files shared yet</p>
                <p class="text-gray-500 text-sm">Files shared in this chat will appear here</p>
            </div>

            {{-- Files Grid --}}
            <div x-show="!isLoadingAttachments && attachmentsList.length > 0"
                 class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                 <template x-for="file in attachmentsList" :key="file.id">
                    <div class="bg-gray-800 rounded-lg overflow-hidden hover:bg-gray-750 transition-colors group">
                        {{-- Image Preview --}}
                        <div x-show="file.mime.startsWith('image/')" class="aspect-square">
                            <img :src="file.url" :alt="file.name"
                                 class="w-full h-full object-cover cursor-pointer"
                                 @click="window.open(file.url, '_blank')">
                        </div>

                        {{-- File Icon --}}
                        <div x-show="!file.mime.startsWith('image/')"
                             class="aspect-square flex items-center justify-center bg-gray-700">
                             <i data-lucide="file" class="w-8 h-8 text-gray-400"></i>
                        </div>

                        {{-- File Info --}}
                        <div class="p-3">
                            <p class="text-xs text-white truncate font-medium" x-text="file.name"></p>
                            <p class="text-[10px] text-gray-500 mt-1" x-text="file.uploaded_at"></p>
                            <a :href="file.url" download
                               class="inline-flex items-center gap-1 text-[10px] text-indigo-400 hover:text-indigo-300 mt-1">
                               <i data-lucide="download" class="w-3 h-3"></i>
                               Download
                            </a>
                        </div>
                    </div>
                 </template>
            </div>
          </div>
     </div>

</div>