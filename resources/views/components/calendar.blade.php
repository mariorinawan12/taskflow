{{-- Calendar Component --}}
<div x-data="calendarComponent()">

    {{-- Sub-view Navigation --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-1">
            <button
                @click="view = 'month'"
                :class="view === 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-gray-300'"
                class="px-3 py-2 text-xs font-medium rounded-lg transition-colors">
                Month
            </button>
            <button
                @click="view = 'timeline'"
                :class="view === 'timeline' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-gray-300'"
                class="px-3 py-2 text-xs font-medium rounded-lg transition-colors">
                Timeline
            </button>
            <button
                @click="view = 'agenda'"
                :class="view === 'agenda' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:text-gray-300'"
                class="px-3 py-2 text-xs font-medium rounded-lg transition-colors">
                Agenda
            </button>
        </div>

        <div class="flex items-center gap-2" x-show="view === 'month'">
            <button
                @click="previousMonth()"
                class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
            <span class="text-white font-medium text-sm" x-text="currentMonthYear"></span>
            <button
                @click="nextMonth()"
                class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    {{-- Month View --}}
    <div x-show="view === 'month'" x-cloak>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">

            {{-- Calendar Header (days of week) --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="p-2 text-center text-xs font-medium text-gray-500">Sun</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Mon</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Tue</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Wed</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Thu</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Fri</div>
                <div class="p-2 text-center text-xs font-medium text-gray-500">Sat</div>
            </div>

            {{-- Calendar Body --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="day in calendarDays" :key="day.date">
                    <div class="min-h-[100px] p-2 border border-gray-800 rounded-lg"
                         :class="day.isCurrentMonth ? 'bg-gray-800/50' : 'bg-gray-900/50'">

                         {{-- Date Number --}}
                         <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-medium"
                                  :class="day.isToday ? 'text-indigo-400' : (day.isCurrentMonth ? 'text-gray-300' : 'text-gray-600')"
                                  x-text="day.day"></span>
                         </div>

                         {{-- Task for this day --}}
                         <div class="space-y-1">
                            <template x-for="task in day.tasks" :key="task.id">
                                <div class="px-2 py-1 bg-gray-700 rounded text-[10px] text-gray-300 truncate cursor-pointer hover:bg-gray-600 transition-colors"
                                     :title="task.title"
                                     @click="openTask(task)">
                                     <span x-text="task.title"></span>
                                </div>
                            </template>
                         </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Timeline View --}}
    <div x-show="view === 'timeline'" x-cloak>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4">
            <div class="space-y-3">
                <template x-for="task in timelineTasks" :key="task.id">
                    <div class="flex items-center gap-4 p-3 bg-gray-800/50 rounded-lg">
                        <div class="w-48 shrink-0">
                            <h4 class="text-sm font-medium text-white truncate" x-text="task.title"></h4>
                            <p class="text-xs text-gray-400" x-text="task.assignees?.[0]?.name || 'Unassigned'"></p>
                        </div>
                        
                        <div class="flex-1 relative h-6 bg-gray-700 rounded">
                            <div class="absolute inset-y-0 left-0 rounded"
                                 :class="task.isOverdue ? 'bg-red-500' : 'bg-indigo-500'"
                                 :style="`width: ${Math.min(100, (task.duration / 30) * 100)}%`">
                            </div>
                            <span class="absolute inset-0 flex items-center px-2 text-xs text-white font-medium"
                                x-text="`${task.duration} days`"></span>
                        </div>

                        <div class="text-xs text-gray-400 w-20 text-right"
                            x-text="new Date(task.due_date).toLocaleDateString()">
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Agenda View --}}
    <div x-show="view === 'agenda'" x-cloak>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 space-y-6">
            <div x-show="agendaTasks.overdue.length > 0">
                <h3 class="text-sm font-semibold text-red-400 mb-3 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    Overdue (<span x-text="agendaTasks.overdue.length"></span>)
                </h3>
                <div class="space-y-2">
                    <template x-for="task in agendaTasks.overdue" :key="task.id">
                        <div class="flex items-center gap-3 p-3 bg-red-500/10 border border-red-500/20 rounded-lg cursor-pointer hover:bg-red-500/20 transition-colors"
                            @click="openTask(task)">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-white" x-text="task.title"></h4>
                                <p class="text-xs text-gray-400" x-text="new Date(task.due_date).toLocaleDateString()"></p>
                            </div>
                            <span class="text-xs px-2 py-1 bg-red-500/20 text-red-400 rounded" x-text="task.priority"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="agendaTasks.today.length > 0">
                <h3 class="text-sm font-semibold text-indigo-400 mb-3 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Today (<span x-text="agendaTasks.today.length"></span>)
                </h3>
                <div class="space-y-2">
                    <template x-for="task in agendaTasks.today" :key="task.id">
                        <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg cursor-pointer hover:bg-gray-700/50 transition-colors"
                            @click="openTask(task)">
                            <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-white" x-text="task.title"></h4>
                                <p class="text-xs text-gray-400" x-text="task.assignees?.[0]?.name || 'Unassigned'"></p>
                            </div>
                            <span class="text-xs px-2 py-1 bg-gray-700 text-gray-300 rounded" x-text="task.priority"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="agendaTasks.tomorrow.length > 0">
                <h3 class="text-sm font-semibold text-gray-400 mb-3">Tomorrow (<span x-text="agendaTasks.tomorrow.length"></span>)</h3>
                <div class="space-y-2">
                    <template x-for="task in agendaTasks.tomorrow" :key="task.id">
                        <div class="flex items-center gap-3 p-3 bg-gray-800/30 rounded-lg cursor-pointer hover:bg-gray-700/30 transition-colors"
                             @click="openTask(task)">
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-300" x-text="task.title"></h4>
                                <p class="text-xs text-gray-500" x-text="task.assignees?.[0]?.name || 'Unassigned'"></p>
                            </div>
                            <span class="text-xs px-2 py-1 bg-gray-800 text-gray-400 rounded" x-text="task.priority"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="agendaTasks.thisWeek.length > 0">
                <h3 class="text-sm font-semibold text-gray-400 mb-3">This Week (<span x-text="agendaTasks.thisWeek.length"></span>)</h3>
                <div class="space-y-2">
                    <template x-for="task in agendaTasks.thisWeek" :key="task.id">
                        <div class="flex items-center gap-3 p-3 bg-gray-800/20 rounded-lg cursor-pointer hover:bg-gray-700/20 transition-colors"
                            @click="openTask(task)">
                            <div class="w-2 h-2 bg-gray-600 rounded-full"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-400" x-text="task.title"></h4>
                                <p class="text-xs text-gray-600" x-text="new Date(task.due_date).toLocaleDateString()"></p>
                            </div>
                            <span class="text-xs px-2 py-1 bg-gray-800 text-gray-500 rounded" x-text="task.priority"></span>
                        </div>
                    </template>
                </div>
            </div>
            <div x-show="agendaTasks.overdue.length === 0 && agendaTasks.today.length === 0 && agendaTasks.tomorrow.length === 0 && agendaTasks.thisWeek.length === 0">
                <p class="text-gray-500 text-center py-8">No upcoming tasks</p>
            </div>
        </div>
    </div>

</div>

{{-- Calendar Component Script --}}
<script>
    function calendarComponent() {
        return {
            view: 'month',
            currentDate: new Date(),
            tasks: @json($tasks),

            get currentMonthYear() {
                return this.currentDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });
            },

            get calendarDays() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());

                const days = [];
                const today = new Date();

                for (let i = 0 ; i < 42; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);

                    const dayTasks = this.tasks.filter(task => {
                        if (!task.due_date) return false;
                        const taskDate = new Date(task.due_date);
                        return taskDate.toDateString() === date.toDateString();
                    });

                    days.push({
                        date: date.toISOString(),
                        day: date.getDate(),
                        isCurrentMonth: date.getMonth() === month,
                        isToday: date.toDateString() === today.toDateString(),
                        tasks: dayTasks
                    });
                }
                return days;
            },

            get timelineTasks() {
                return this.tasks
                    .filter(task => task.due_date)
                    .map(task => {
                        const dueDate = new Date(task.due_date);
                        const createdDate = new Date(task.created_at);
                        const duration = Math.max(1, Math.ceil((dueDate - createdDate) / (1000 * 60 * 60 * 24)));

                        return {
                            ...task,
                            startDate: createdDate,
                            endDate: dueDate,
                            duration: duration,
                            isOverdue: dueDate < new Date() && task.status !== 'done'
                        };
                    })
                    .sort((a,b) => new Date(a.due_date) - new Date(b.due_date));
            },

            get agendaTasks() {
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(today.getDate() + 1);

                const thisWeekEnd = new Date(today);
                thisWeekEnd.setDate(today.getDate() + 7);

                return {
                    overdue: this.tasks.filter(task => 
                        task.due_date && new Date(task.due_date) < today && task.status !== 'done'
                    ),
                    today: this.tasks.filter(task =>
                        task.due_date && new Date(task.due_date).toDateString() === today.toDateString()
                    ),
                    tomorrow: this.tasks.filter(task => 
                        task.due_date && new Date(task.due_date).toDateString() === tomorrow.toDateString()
                    ),
                    thisWeek: this.tasks.filter(task => {
                        if (!task.due_date) return false;
                        const dueDate = new Date(task.due_date);
                        return dueDate > tomorrow && dueDate <= thisWeekEnd;
                    })
                };
            },

            previousMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                this.currentDate = new Date(this.currentDate);
            },

            nextMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                this.currentDate = new Date(this.currentDate);
            },

            openTask(task) {
                window.location.href = `/{{ $workspace->slug }}/projects/{{ $project->id }}/tasks/${task.id}`;
            }

        }
    }
</script>