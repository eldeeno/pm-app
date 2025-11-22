<template>
    <Head title="Member Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ project.name }} Tasks
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Project Details -->
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

                        <!-- Tasks Section -->
                        <hr class="my-3" />
                        <button
                            @click="showTaskModal = true"
                            :disabled="isProjectOverdue"
                            :class="{
                                'cursor-not-allowed opacity-50': isProjectOverdue,
                                'cursor-pointer hover:text-green-600 hover:border-green-600': !isProjectOverdue,
                            }"
                            class="text-green-400 border-2 p-2 text-sm border-green-400 rounded-md font-semibold my-2 inline-flex"
                        >
                            <svg
                                class="w-4 h-4 my-auto mr-1"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                            Add Task
                        </button>
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
                                <div class="flex justify-between">
                                    <h4 class="text-lg font-semibold text-gray-800">{{ task.name }}</h4>
                                    <span>
                                        <button v-if="task.status === 'pending'" @click="editTask(task)">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="w-5 h-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <p class="text-gray-600 mt-2">{{ task.description }}</p>
                                <div class="mt-4">
                                    <span class="text-sm text-gray-500">Status: {{ formatDate(task.start_date) }}</span>
                                    <span class="text-sm text-gray-500 ml-4">Due: {{ formatDate(task.end_date) }}</span>
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
                        <div class="mt-6">
                            <SecondaryButton @click="goBack">Back to Projects</SecondaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Modal -->
        <div v-if="showTaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between">
                    <h2 class="text-lg font-semibold">{{ modalTitle }}</h2>
                    <a class="text-gray-400 hover:text-gray-300 font-semibold cursor-pointer" @click="closeModal">x</a>
                </div>
                <div class="flex flex-col my-4">
                    <label class="block font-semibold text-gray-600" for="task_name">Task Name</label>
                    <input
                        type="text"
                        v-model="taskForm.name"
                        placeholder="Task name"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <label class="label" v-if="$page.props.errors && $page.props.errors.name">
                        <span class="label-text-alt text-red-600">{{ $page.props.errors.name }}</span>
                    </label>
                </div>
                <div class="flex flex-col my-2">
                    <label class="block font-semibold text-gray-600" for="task_description">Description</label>
                    <textarea
                        v-model="taskForm.description"
                        placeholder="Description"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                    <label class="label" v-if="$page.props.errors && $page.props.errors.description">
                        <span class="label-text-alt text-red-600">{{ $page.props.errors.description }}</span>
                    </label>
                </div>
                <div class="flex gap-2 my-2">
                    <div class="basis-1/2">
                        <label class="block font-semibold text-gray-600" for="task_start_date">Start Date</label>
                        <input
                            type="date"
                            v-model="taskForm.start_date"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <label class="label" v-if="$page.props.errors && $page.props.errors.start_date">
                            <span class="label-text-alt text-red-600">{{ $page.props.errors.start_date }}</span>
                        </label>
                    </div>
                    <div class="basis-1/2">
                        <label class="block font-semibold text-gray-600" for="task_end_date">End Date</label>
                        <input
                            type="date"
                            v-model="taskForm.end_date"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <label class="label" v-if="$page.props.errors && $page.props.errors.end_date">
                            <span class="label-text-alt text-red-600">{{ $page.props.errors.end_date }}</span>
                        </label>
                    </div>
                </div>
                <div class="flex gap-2 my-2">
                    <div class="w-full">
                        <label class="block font-semibold text-gray-600" for="task_end_date">Select Assignee</label>
                        <select
                            v-model="taskForm.assignee"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Select Assignee</option>
                            <option v-for="member in members" :key="member.id" :value="member.id">
                                {{ member.name }}
                            </option>
                        </select>
                        <label class="label" v-if="$page.props.errors && $page.props.errors.assignee">
                            <span class="label-text-alt text-red-600">{{ $page.props.errors.assignee }}</span>
                        </label>
                    </div>
                </div>
                <!-- Dependency Dropdown (Only show if there are existing tasks) -->
                <div v-if="project.tasks.length > 0" class="flex gap-2 my-2">
                    <div class="w-full">
                        <label class="block font-semibold text-gray-600" for="task_dependency">Dependency</label>
                        <select
                            v-model="taskForm.dependency"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">No Dependency</option>
                            <option v-for="task in project.tasks" :key="task.id" :value="task.id">
                                {{ task.name }}
                            </option>
                        </select>
                        <label class="label" v-if="$page.props.errors && $page.props.errors.dependency">
                            <span class="label-text-alt text-red-600">{{ errors.dependency }}</span>
                        </label>
                    </div>
                </div>
                <div class="flex my-6 gap-2">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <PrimaryButton @click="saveTask">Save</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "Create",
    components: { SecondaryButton, PrimaryButton, AuthenticatedLayout, Head },
    props: {
        project: {
            type: Object,
            required: true,
        },
        members: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            showTaskModal: false,
            modalTitle: "Add Task",
            taskForm: {
                id: null,
                name: "",
                description: "",
                start_date: "",
                end_date: "",
                assignee: "",
                dependency: "",
            },
        };
    },
    computed: {
        isProjectOverdue() {
            const today = new Date();
            const projectEndDate = new Date(this.project.end_date);
            return projectEndDate < today;
        },
    },
    methods: {
        closeModal() {
            this.showTaskModal = false;
            this.resetTaskForm();
            router.visit(route("project.tasks", this.project.id), {
                preserveScroll: true,
                preserveState: true,
            });
        },
        resetTaskForm() {
            this.taskForm = {
                id: null,
                name: "",
                description: "",
                start_date: "",
                end_date: "",
                assignee: "",
                dependency: "",
            };
        },
        saveTask() {
            if (this.taskForm.id) {
                // Update existing task
                router.put(`/projects/${this.project.id}/tasks/${this.taskForm.id}`, this.taskForm, {
                    onSuccess: () => {
                        this.closeModal();
                    },
                    onError: (errors) => {
                        console.error("Task update failed:", errors);
                    },
                });
            } else {
                // Create new task
                router.post(`/projects/${this.project.id}/tasks`, this.taskForm, {
                    onSuccess: () => {
                        this.closeModal();
                    },
                    onError: (errors) => {
                        console.error("Task creation failed:", errors);
                    },
                });
            }
        },
        editTask(task) {
            this.showTaskModal = true;
            this.modalTitle = "Edit Task";
            this.taskForm = {
                id: task.id,
                name: task.name,
                description: task.description,
                start_date: task.start_date,
                end_date: task.end_date,
                assignee: task.assignee_id,
                dependency: task.dependency_id,
            };
        },
        goBack() {
            router.get("/projects");
        },
        formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('en-GB');
        },
    },
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
