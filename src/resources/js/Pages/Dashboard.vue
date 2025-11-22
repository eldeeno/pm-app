<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

export default {
    name: "Dashboard",
    components: { AuthenticatedLayout, Head },
    props: {
        projects: {
            type: Array,
            required: true,
        },
        tasks: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const activeTab = ref('projects');

        const selectedStatus = ref('all');

        const filteredTasks = () => {
            if (selectedStatus.value === 'all') {
                return props.tasks;
            }
            return props.tasks.filter(task => {
                if (selectedStatus.value === 'overdue') {
                    return task.is_overdue;
                }
                return task.status === selectedStatus.value;
            });
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-GB');
        };

        const getBadgeClass = (status, isOverdue) => {
            if (isOverdue) {
                return 'bg-red-100 text-red-800';
            }
            switch (status) {
                case 'completed':
                    return 'bg-green-100 text-green-800';
                case 'in_progress':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        };

        return {
            activeTab,
            selectedStatus,
            filteredTasks,
            formatDate,
            getBadgeClass,
        };
    },
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-end my-4">
                            <a
                                :href="route('generate.report')"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 my-auto mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Generate PDF Report
                            </a>
                        </div>

                        <!-- Tab Navigation -->
                        <div class="mb-6 border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button
                                    @click="activeTab = 'projects'"
                                    :class="{
                                        'border-blue-500 text-blue-600': activeTab === 'projects',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'projects',
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                >
                                    Projects Overview
                                </button>
                                <button
                                    @click="activeTab = 'tasks'"
                                    :class="{
                                        'border-blue-500 text-blue-600': activeTab === 'tasks',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'tasks',
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                >
                                    Tasks Overview
                                </button>
                            </nav>
                        </div>

                        <!-- Projects Tab -->
                        <div v-if="activeTab === 'projects'">
                            <h3 class="text-lg font-semibold mb-4">Projects Overview</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Project Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Tasks
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Completed Tasks
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Progress
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="project in projects" :key="project.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ project.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ project.total_tasks }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ project.completed_tasks }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <progress class="progress progress-success w-32" :value="project.progress" max="100"></progress>
                                            <span class="ml-2 text-sm text-gray-600">
                                                {{ project.progress }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tasks Tab -->
                        <div v-if="activeTab === 'tasks'">
                            <h3 class="text-lg font-semibold mb-4">Tasks Overview</h3>

                            <!-- Filter by Status -->
                            <div class="mb-6 w-1/4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select
                                    id="status"
                                    v-model="selectedStatus"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                >
                                    <option value="all">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>

                            <!-- Tasks Table -->
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Task Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Project
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Due Date
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="task in filteredTasks()"
                                    :key="task.id"
                                    :class="{ 'bg-red-50': task.is_overdue }"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ task.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ task.project_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="getBadgeClass(task.status, task.is_overdue)"
                                            class="px-3 py-1 text-sm font-semibold rounded-full"
                                        >
                                            {{ task.status === 'in_progress' ? 'In Progress' : task.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ formatDate(task.end_date) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Add custom styles if needed */
</style>
