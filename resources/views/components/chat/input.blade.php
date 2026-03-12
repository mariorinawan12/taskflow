{{-- Chat Input Component --}}
<div class="p-3 bg-gray-800/30 border-t border-gray-800">
    <form @submit.prevent="sendMessage" enctype="multipart/form-data">
        <div
            class="relative bg-gray-900 border border-gray-700 rounded-xl focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all">

            {{-- File Preview --}}
            <div x-show="selectedFiles.length > 0" class="p-3 pb-0">
                <div class="flex flex-wrap gap-2">
                    <template x-for="(file, index) in selectedFiles" :key="index">
                        <div class="flex items-center gap-2 bg-gray-800 rounded-lg p-2 text-xs">
                            <i data-lucide="file" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-300 truncate max-w-[120px]" x-text="file.name"></span>
                            <button type="button" @click="removeFile(index)"
                                class="text-gray-500 hover:text-red-400 ml-1">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-end gap-2 p-3">
                <div class="flex-1">
                    <textarea x-model="body" rows="1" :placeholder="$attrs.placeholder || 'Write a message...'"
                        class="w-full bg-transparent text-sm text-white placeholder-gray-500 resize-none outline-none border-none focus:ring-0"
                        style="field-sizing: content; max-height: 120px;"
                        @keydown.enter.prevent="if (!$event.shiftKey) sendMessage()"
                        @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"></textarea>
                </div>

                <div class="flex items-center gap-1">
                    <input type="file" x-ref="fileInput" @change="handleFileSelect" multiple class="hidden">
                    <button type="button" @click="$refs.fileInput.click()"
                        class="p-2 text-gray-500 hover:text-indigo-400 hover:bg-gray-800 rounded-lg transition-colors">
                        <i data-lucide="paperclip" class="w-4 h-4"></i>
                    </button>

                    <button type="submit" :disabled="isSending || (!body.trim() && selectedFiles.length === 0)"
                        :class="body.trim() || selectedFiles.length > 0 ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-gray-700/60 text-gray-500 cursor-not-allowed'"
                        class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>