<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    task: Object,
});

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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="task.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Task Details</h2>
                <div class="flex gap-2">
                    <Link 
                        :href="route('tasks.edit', task.id)" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Edit Task
                    </Link>
                    <Link 
                        :href="route('tasks.index')" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Back to Tasks
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Task Header -->
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ task.title }}</h1>
                            
                            <div class="flex gap-3 mb-4">
                                <span 
                                    :class="getPriorityColor(task.priority)"
                                    class="px-3 py-1 text-sm font-semibold rounded-full"
                                >
                                    {{ task.priority.toUpperCase() }} Priority
                                </span>
                                <span 
                                    :class="getStatusColor(task.status)"
                                    class="px-3 py-1 text-sm font-semibold rounded-full"
                                >
                                    {{ task.status.replace('_', ' ').toUpperCase() }}
                                </span>
                            </div>
                        </div>

                        <!-- Task Details -->
                        <div class="space-y-6">
                            <!-- Description -->
                            <div v-if="task.description" class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ task.description }}</p>
                            </div>

                            <!-- Metadata -->
                            <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div v-if="task.category">
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Category</h3>
                                    <p class="text-gray-900 text-lg">📁 {{ task.category }}</p>
                                </div>

                                <div v-if="task.due_date">
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Due Date</h3>
                                    <p class="text-gray-900 text-lg">📅 {{ formatDate(task.due_date) }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Created By</h3>
                                    <p class="text-gray-900 text-lg">👤 {{ task.user.name }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Created At</h3>
                                    <p class="text-gray-900 text-lg">{{ formatDate(task.created_at) }}</p>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            <div class="border-t pt-6">
                                <p class="text-sm text-gray-500">
                                    Last updated: {{ formatDate(task.updated_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>