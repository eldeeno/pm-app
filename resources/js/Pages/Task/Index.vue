<template>
    <Head title="Member Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Tasks
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Grid of Task Cards -->
                        <div v-if="tasks && tasks.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Task Cards -->
                            <div
                                v-for="(task, index) in tasks.data"
                                :key="task.id"
                                class="shadow-md rounded-lg p-6"
                                :class="{
                                    'bg-green-100 text-green-800': task.status === 'completed',
                                    'bg-blue-100 text-blue-800': task.status === 'in_progress',
                                    'bg-red-100 text-red-800': task.status === 'overdue',
                                    'bg-gray-100 text-gray-800': task.status === 'pending',
                                }"
                            >
                                <div class="flex justify-between">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ task.name }}</h4>
                                    <span>
                                        <PrimaryButton @click="viewTask(task.id)" class="tooltip" data-tip="view">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M15 3.75a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0V5.56l-3.97 3.97a.75.75 0 1 1-1.06-1.06l3.97-3.97h-2.69a.75.75 0 0 1-.75-.75Zm-12 0A.75.75 0 0 1 3.75 3h4.5a.75.75 0 0 1 0 1.5H5.56l3.97 3.97a.75.75 0 0 1-1.06 1.06L4.5 5.56v2.69a.75.75 0 0 1-1.5 0v-4.5Zm11.47 11.78a.75.75 0 1 1 1.06-1.06l3.97 3.97v-2.69a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1 0-1.5h2.69l-3.97-3.97Zm-4.94-1.06a.75.75 0 0 1 0 1.06L5.56 19.5h2.69a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 1 1.5 0v2.69l3.97-3.97a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                            </svg>
                                        </PrimaryButton>
                                    </span>
                                </div>
                                <p class="text-gray-600 mt-2">{{ task.description }}</p>
                                <div class="mt-4">
                                    <span class="text-sm text-gray-500">Start Date: {{ formatDate(task.start_date) }}</span>
                                    <span class="text-sm text-gray-500 ml-4">End Date: {{ formatDate(task.end_date) }}</span>
                                </div>
                                <div class="mt-4">
                                    <span
                                        :class="statusBadgeClass(task.status)"
                                        class="badge p-3"
                                    >
                                        {{ statusText(task.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-gray-600">No tasks found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "Index",
    props: {
        tasks: {
            type: Object,
            default: () => ({ data: [] }), // Default to an empty object with a data array
        },
    },
    components: { SecondaryButton, AuthenticatedLayout, PrimaryButton, Head },
    methods: {
        statusBadgeClass(status) {
            switch (status) {
                case 'completed':
                    return 'badge badge-success p-3';
                case 'in_progress':
                    return 'badge badge-info p-3';
                case 'overdue':
                    return 'badge badge-danger p-3';
                case 'pending':
                    return 'badge badge-base p-3';
                default:
                    return 'badge badge-secondary p-3';
            }
        },
        statusText(status) {
            switch (status) {
                case 'completed':
                    return 'Completed';
                case 'in_progress':
                    return 'In Progress';
                case 'overdue':
                    return 'Overdue';
                case 'pending':
                    return 'Pending';
                default:
                    return 'Unknown';
            }
        },
        formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('en-GB'); // Format as dd/mm/yyyy
        },
        viewTask(taskId) {
            this.$inertia.visit(`/user/tasks/${taskId}`);
        },
    },
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
