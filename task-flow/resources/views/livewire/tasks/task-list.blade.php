<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Tasks</h2>
                    <a href="{{ route('livewire.tasks.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Task
                    </a>
                </div>

                @if (session()->has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input wire:model.live="search" type="text" placeholder="Search tasks..." 
                           class="border rounded px-4 py-2">
                    
                    <select wire:model.live="statusFilter" class="border rounded px-4 py-2">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>

                    <select wire:model.live="priorityFilter" class="border rounded px-4 py-2">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>

                    <select wire:model.live="categoryFilter" class="border rounded px-4 py-2">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="clearFilters" class="mb-4 text-sm text-blue-600 hover:text-blue-800">
                    Clear Filters
                </button>

                <!-- Tasks List -->
                <div class="space-y-4">
                    @forelse($tasks as $task)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-lg font-semibold {{ $task->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                                            {{ $task->title }}
                                        </h3>
                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded
                                            @if($task->status === 'completed') bg-green-100 text-green-800
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>

                                    @if($task->description)
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($task->description, 100) }}</p>
                                    @endif

                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        @if($task->category)
                                            <span>📁 {{ $task->category }}</span>
                                        @endif
                                        @if($task->due_date)
                                            <span class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-red-600' : '' }}">
                                                📅 {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button wire:click="toggleStatus({{ $task->id }})" 
                                            class="text-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                                        {{ $task->status === 'completed' ? 'Reopen' : 'Complete' }}
                                    </button>
                                    <a href="{{ route('livewire.tasks.edit', $task) }}" 
                                       class="text-sm px-3 py-1 bg-blue-200 hover:bg-blue-300 rounded">
                                        Edit
                                    </a>
                                    <button wire:click="deleteTask({{ $task->id }})" 
                                            wire:confirm="Are you sure you want to delete this task?"
                                            class="text-sm px-3 py-1 bg-red-200 hover:bg-red-300 rounded">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            No tasks found. Create your first task!
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>