<template>
    <Head title="View Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                View Project: {{ project.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex flex-col space-y-4">
                            <div>
                                <label class="block font-bold text-gray-600">Project Name</label>
                                <p class="mt-1">{{ project.name }}</p>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600">Description</label>
                                <p class="mt-1">{{ project.description }}</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block font-bold text-gray-600">Start Date</label>
                                    <p class="mt-1">{{ project.start_date }}</p>
                                </div>
                                <div class="w-1/2">
                                    <label class="block font-bold text-gray-600">End Date</label>
                                    <p class="mt-1">{{ project.end_date }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-gray-800 my-4">Tasks</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Task Cards -->
                                <div
                                    v-for="task in project.tasks"
                                    :key="task.id"
                                    class="shadow-md rounded-lg p-6"
                                    :class="{
                                    'bg-green-100 text-green-800': task.status === 'completed',
                                    'bg-blue-100 text-blue-800': task.status === 'in_progress',
                                    'bg-red-100 text-red-800': task.status === 'overdue',
                                    'bg-gray-100 text-gray-800': task.status === 'pending',
                                }"
                                >
                                    <h4 class="text-lg font-semibold text-gray-800">{{ task.name }}</h4>
                                    <p class="text-gray-600 mt-2">{{ task.description }}</p>
                                    <div class="mt-4">
                                        <span class="text-sm text-gray-500">Status: {{ task.status }}</span>
                                        <span class="text-sm text-gray-500 ml-4">Due: {{ task.end_date }}</span>
                                    </div>
                                    <div class="mt-4">
                                    <span
                                        :class="{
                                            'badge-success': task.status === 'completed',
                                            'badge-info': task.status === 'in_progress',
                                            'badge-error': task.status === 'overdue',
                                            'badge-neutral': task.status === 'pending',
                                        }"
                                        class="badge p-3"
                                    >
                                        {{ task.status }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="flex justify-between mt-8">
                            <SecondaryButton @click="goBack">Back to Projects</SecondaryButton>
                            <SecondaryButton @click="editProject(project.id)">Edit</SecondaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "View",
    props: ['project'],
    components: { SecondaryButton, AuthenticatedLayout, Head },
    methods: {
        formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('en-GB'); // Format as dd/mm/yyyy
        },
        goBack() {
            this.$inertia.get('/projects');
        },
        editProject(id) {
            this.$inertia.get(`/projects/${id}/edit`);
        }
    }
}
</script>

<style scoped>
/* Add custom styles if needed */
</style>
