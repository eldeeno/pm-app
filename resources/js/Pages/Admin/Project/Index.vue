<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-end mb-2">
                            <PrimaryButton
                                v-if="$page.props.auth.user.roles[0] === 'Admin'"
                                @click="createProject">Add Project</PrimaryButton>
                        </div>
                        <div class="overflow-x-auto overflow-y-hidden">
                            <table class="table table-sm w-full text-sm">
                                <thead>
                                <tr>
                                    <th class="uppercase tracking-wider text-gray-400">S/No.</th>
                                    <th class="uppercase tracking-wider text-gray-400">Name</th>
                                    <th class="uppercase tracking-wider text-gray-400">Start Date</th>
                                    <th class="uppercase tracking-wider text-gray-400">End Date</th>
                                    <th class="uppercase tracking-wider text-gray-400">Created By</th>
                                    <th class="uppercase tracking-wider text-gray-400">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-if="projects" v-for="(project, index) in projects.data">
                                    <td>{{ index + projects.from }}</td>
                                    <td>{{ project.name }}</td>
                                    <td>{{ project.start_date }}</td>
                                    <td>{{ project.end_date }}</td>
                                    <td>{{ project.user.name }}</td>
                                    <td>
                                        <div class="inline-flex space-x-1">
                                            <SecondaryButton @click="viewProject(project.id)" class="tooltip" data-tip="view">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd" d="M15 3.75a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0V5.56l-3.97 3.97a.75.75 0 1 1-1.06-1.06l3.97-3.97h-2.69a.75.75 0 0 1-.75-.75Zm-12 0A.75.75 0 0 1 3.75 3h4.5a.75.75 0 0 1 0 1.5H5.56l3.97 3.97a.75.75 0 0 1-1.06 1.06L4.5 5.56v2.69a.75.75 0 0 1-1.5 0v-4.5Zm11.47 11.78a.75.75 0 1 1 1.06-1.06l3.97 3.97v-2.69a.75.75 0 0 1 1.5 0v4.5a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1 0-1.5h2.69l-3.97-3.97Zm-4.94-1.06a.75.75 0 0 1 0 1.06L5.56 19.5h2.69a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 1 1.5 0v2.69l3.97-3.97a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                                </svg>
                                            </SecondaryButton>
                                            <SecondaryButton @click="editProject(project.id)" class="tooltip" data-tip="edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </SecondaryButton>
                                            <SecondaryButton @click="addTask(project.id)" class="tooltip" data-tip="add task">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                                                </svg>
                                            </SecondaryButton>
                                            <PrimaryButton
                                                v-if="$page.props.auth.user.roles[0] === 'Admin'"
                                                @click="deleteProject(project.id)" class="tooltip bg-red-600 hover:bg-red-400" data-tip="delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </PrimaryButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td>No Data Available</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-center">
                            <Pagination :pagination="projects" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "Index",
    props: ['projects', 'role', 'permissions'],
    components: {SecondaryButton, PrimaryButton, Pagination, AuthenticatedLayout, Head},
    data() {
        return {

        }
    },
    methods: {
        createProject(id) {
            router.get('/projects/create');
        },
        viewProject(id) {
            router.get(`/projects/${id}`);
        },
        editProject(id) {
            router.get(`/projects/${id}/edit`);
        },
        deleteProject(id) {
            if (confirm("Are you sure you want to delete this project?")) {
                router.delete(`/projects/${id}`, {
                    onSuccess: () => {
                        console.log("Project deleted successfully!");
                    },
                    onError: () => {
                        console.error("Failed to delete project.");
                    }
                });
            }
        },
        addTask(id) {
            router.get(route('project.tasks', id));
        }
    }
}

</script>

<style scoped>

</style>
