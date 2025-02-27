<template>
    <Head title="Member Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight text-gray-800 inline-flex">Task: <b class="text-info"> {{task.name}} </b></h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Error Alert -->
                <div v-if="showError" role="alert" class="alert alert-error bg-red-600 text-white mb-6">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        class="stroke-info h-6 w-6 shrink-0">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ errorMessage }}</span>
                    <div>
                        <button @click="showError = false" class="btn btn-sm btn-circle btn-error">x</button>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-lg sm:rounded-lg">
                    <div class="p-8">
                        <!-- Task Details -->
                        <div class="mb-8">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ task.name }}</h3>
                            <p class="text-lg text-gray-600">{{ task.description }}</p>
                        </div>

                        <!-- Task Status -->
                        <div class="mb-8">
                            <p :class="statusBadgeClass(task.status)" class="inline-block">
                                {{ statusText(task.status) }}
                            </p>
                        </div>

                        <!-- Update Status Buttons -->
                        <div class="flex space-x-4">
                            <!-- Start Task Button (Visible when status is "pending") -->
                            <button
                                v-if="task.status === 'pending'"
                                @click="updateStatus('in_progress')"
                                class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300"
                            >
                                Start Task
                            </button>

                            <!-- Mark as Completed Button (Visible when status is not "pending") -->
                            <button
                                v-if="task.status !== 'pending'"
                                @click="updateStatus('completed')"
                                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300"
                                :class="{ 'opacity-50 cursor-not-allowed': task.status === 'completed' }"
                                :disabled="task.status === 'completed'"
                            >
                                Mark as Completed
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch } from 'vue';

export default {
    name: "Show",
    components: { AuthenticatedLayout, Head },
    props: {
        task: {
            type: Object,
            required: true,
        },
        flash: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props) {
        const showError = ref(false);
        const errorMessage = ref('');

        watch(
            () => props.flash.error,
            (newError) => {
                if (newError) {
                    showError.value = true;
                    errorMessage.value = newError;

                    setTimeout(() => {
                        showError.value = false;
                        errorMessage.value = '';
                    }, 3000);
                }
            },
            { immediate: true }
        );

        return {
            showError,
            errorMessage,
        };
    },
    methods: {
        statusBadgeClass(status) {
            switch (status) {
                case "completed":
                    return "bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold";
                case "in_progress":
                    return "bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold";
                case "overdue":
                    return "bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-semibold";
                case "pending":
                    return "bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold";
                default:
                    return "bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold";
            }
        },
        statusText(status) {
            switch (status) {
                case "completed":
                    return "Completed";
                case "in_progress":
                    return "In Progress";
                case "overdue":
                    return "Overdue";
                case "pending":
                    return "Pending";
                default:
                    return "Unknown";
            }
        },
        updateStatus(newStatus) {
            router.patch(`/user/tasks/${this.task.id}`, {
                status: newStatus,
            });
        },
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>
