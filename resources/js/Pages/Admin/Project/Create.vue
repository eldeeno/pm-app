<template>
    <Head title="Create Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Create Project
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form>
                        <div class="flex flex-col w-full p-6 text-gray-900">
                            <div class="flex flex-col md:flex-row my-4">
                                <div class="w-full">
                                    <label class="block font-semibold text-gray-600" for="name">Project Name</label>
                                    <input type="text" v-model="form.name" placeholder="Project name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                    <label class="label" v-if="errors && errors.name">
                                        <span class="label-text-alt text-red-600">{{ errors.name }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row my-2">
                                <div class="w-full">
                                    <label class="block font-semibold text-gray-600" for="name">Description</label>
                                    <textarea v-model="form.description" placeholder="Description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    <label class="label" v-if="errors && errors.name">
                                        <span class="label-text-alt text-red-600">{{ errors.name }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-2 my-2">
                                <div class="basis-1/2">
                                    <label class="block font-semibold text-gray-600" for="lastname">Start Date</label>
                                    <input type="date" v-model="form.start_date" placeholder="State Date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                    <label class="label" v-if="errors && errors.start_date">
                                        <span class="label-text-alt text-red-600">{{ errors.start_date }}</span>
                                    </label>
                                </div>
                                <div class="basis-1/2">
                                    <label class="block font-semibold text-gray-600" for="lastname">End Date</label>
                                    <input type="date" v-model="form.end_date" placeholder="End Date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                    <label class="label" v-if="errors && errors.end_date">
                                        <span class="label-text-alt text-red-600">{{ errors.end_date }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex my-6 gap-2">
                                <SecondaryButton @click="goBack">Cancel</SecondaryButton>
                                <PrimaryButton @click="createProject">Save</PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {reactive} from "vue";

export default {
    name: "Create",
    props: ['errors'],
    components: {SecondaryButton, PrimaryButton, Head, AuthenticatedLayout},
    data() {
        return {
            form: reactive({
                name: '',
                description: '',
                start_date: '',
                end_date: '',
            }),
        }
    },
    methods: {
        createProject(event) {
            event.preventDefault();
            router.post('/projects', this.form)
        },
        addTask() {
            const { name, start_date, end_date } = this.taskForm;
            const projectStartDate = new Date(this.form.start_date);
            const projectEndDate = new Date(this.form.end_date);
            const taskStartDate = new Date(start_date);
            const taskEndDate = new Date(end_date);

            if (!name || !start_date || !end_date) {
                alert("Please fill in all required fields.");
                return;
            }

            if (!name || !start_date || !end_date) {
                alert("Please fill in all required fields.");
                return;
            }

            if (taskStartDate < projectStartDate) {
                alert("Task start date cannot be earlier than the project start date.");
                return;
            }

            if (taskStartDate > projectEndDate) {
                alert("Task start date cannot be later than the project end date.");
                return;
            }

            if (taskEndDate < projectStartDate) {
                alert("Task end date cannot be earlier than the project start date.");
                return;
            }

            if (taskEndDate > projectEndDate) {
                alert("Task end date cannot be later than the project end date.");
                return;
            }

            if (taskStartDate > taskEndDate) {
                alert("Task start date cannot be later than the task end date.");
                return;
            }

            if (this.editingIndex !== null) {
                this.tasks[this.editingIndex] = { ...this.taskForm };
                this.editingIndex = null;
            } else {
                this.tasks.push({ ...this.taskForm });
            }

            this.resetTaskForm();
            this.showTaskModal = false;
        },
        formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('en-GB');
        },
        editTask(task, index) {
            this.taskForm = { ...task };
            this.editingIndex = index;
            this.modalTitle = 'Edit Task'
            this.showTaskModal = true;
        },
        removeTask(index) {
            if (confirm('Are you sure you want to delete this task?')) {
                this.tasks.splice(index, 1);
            }
        },
        closeModal() {
            this.showTaskModal = false;
            this.resetTaskForm();
        },
        resetTaskForm() {
            this.taskForm = {
                name: "",
                description: "",
                start_date: "",
                end_date: "",
                assignee: "",
            };
            this.modalTitle = 'Add task';
        },
        goBack() {
            router.get('/projects');
        }
    }
}
</script>

<style scoped>

</style>
