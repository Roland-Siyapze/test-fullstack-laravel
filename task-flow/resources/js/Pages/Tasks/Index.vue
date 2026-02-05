<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    tasks: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const priority = ref(props.filters.priority || '');
const category = ref(props.filters.category || '');

watch([search, status, priority, category], () => {
    router.get('/tasks', {
        search: search.value,
        status: status.value,
        priority: priority.value,
        category: category.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const clearFilters = () => {
    search.value = '';
    status.value = '';
    priority.value = '';
    category.value = '';
};

const deleteTask = (taskId) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/tasks/${taskId}`);
    }
};

const getPriorityColor = (priority) => {
    const colors = {
        urgent: 'bg-red-100 text-red-800',
        high: 'bg-orange-100 text-orange-800',
        medium: 'bg-yellow-100 text-yellow-800',
        low: 'bg-green-100 text-green-800',
    };
    return colors[priority] || 'bg-gray-100 text-gray-800';
};

const getStatusColor = (status) => {
    const colors = {
        completed: 'bg-green-100 text-green-800',
        in_progress: 'bg-blue-100 text-blue-800',
        pending: 'bg-gray-100 text-gray-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
                <Link :href="route('tasks.create')" 
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Task
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Filters -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input 
                                v-model="search" 
                                type="text" 
                                placeholder="Search tasks..." 
                                class="border rounded px-4 py-2"
                            />
                            
                            <select v-model="status" class="border rounded px-4 py-2">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>

                            <select v-model="priority" class="border rounded px-4 py-2">
                                <option value="">All Priorities</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>

                            <select v-model="category" class="border rounded px-4 py-2">
                                <option value="">All Categories</option>
                            </select>
                        </div>

                        <button 
                            @click="clearFilters" 
                            class="mb-4 text-sm text-blue-600 hover:text-blue-800"
                        >
                            Clear Filters
                        </button>

                        <!-- Tasks Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div 
                                v-for="task in tasks.data" 
                                :key="task.id"
                                class="border rounded-lg p-4 hover:shadow-lg transition"
                            >
                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold mb-2">{{ task.title }}</h3>
                                    <div class="flex gap-2 mb-2">
                                        <span 
                                            :class="getPriorityColor(task.priority)"
                                            class="px-2 py-1 text-xs rounded"
                                        >
                                            {{ task.priority }}
                                        </span>
                                        <span 
                                            :class="getStatusColor(task.status)"
                                            class="px-2 py-1 text-xs rounded"
                                        >
                                            {{ task.status.replace('_', ' ') }}
                                        </span>
                                    </div>
                                </div>

                                <p v-if="task.description" class="text-gray-600 text-sm mb-3">
                                    {{ task.description.substring(0, 100) }}{{ task.description.length > 100 ? '...' : '' }}
                                </p>

                                <div class="text-sm text-gray-500 mb-4">
                                    <div v-if="task.category">📁 {{ task.category }}</div>
                                    <div v-if="task.due_date">📅 {{ new Date(task.due_date).toLocaleDateString() }}</div>
                                </div>

                                <div class="flex justify-between gap-2">
                                    <Link 
                                        :href="route('tasks.show', task.id)"
                                        class="text-sm px-3 py-1 bg-blue-200 hover:bg-blue-300 rounded"
                                    >
                                        View
                                    </Link>
                                    <Link 
                                        :href="route('tasks.edit', task.id)"
                                        class="text-sm px-3 py-1 bg-yellow-200 hover:bg-yellow-300 rounded"
                                    >
                                        Edit
                                    </Link>
                                    <button 
                                        @click="deleteTask(task.id)"
                                        class="text-sm px-3 py-1 bg-red-200 hover:bg-red-300 rounded"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-if="tasks.data.length === 0" class="text-center py-12 text-gray-500">
                            No tasks found. Create your first task!
                        </div>

                        <!-- Pagination -->
                        <div v-if="tasks.links.length > 3" class="mt-6 flex justify-center gap-2">
                            <Link 
                                v-for="link in tasks.links" 
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    'px-4 py-2 border rounded',
                                    link.active ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>