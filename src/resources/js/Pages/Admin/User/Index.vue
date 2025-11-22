<template>
    <Head title="Manage Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Manage Users
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Create New User Button -->
                        <div class="flex justify-end mb-6">
                            <PrimaryButton @click="showModal = true">
                                Create New User
                            </PrimaryButton>
                        </div>

                        <!-- Users Table -->
                        <table class="table table-sm w-full text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="uppercase tracking-wider text-gray-400">
                                    Name
                                </th>
                                <th class="uppercase tracking-wider text-gray-400">
                                    Email
                                </th>
                                <th class="uppercase tracking-wider text-gray-400">
                                    Role
                                </th>
                                <th class="uppercase tracking-wider text-gray-400">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-500">
                            <tr v-for="user in users" :key="user.id">
                                <td class="whitespace-nowrap">
                                    {{ user.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ user.email }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ user.role }}
                                </td>
                                <td class="">
                                    <div class="inline-flex space-x-1">
<!--                                        <SecondaryButton @click="editUser(user.id)" class="tooltip" data-tip="edit">-->
<!--                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">-->
<!--                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />-->
<!--                                            </svg>-->
<!--                                        </SecondaryButton>-->

                                        <PrimaryButton @click="deleteUser(user.id)" class="tooltip bg-red-600 hover:bg-red-400" data-tip="delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </PrimaryButton>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <div class="flex justify-between">
                    <h2 class="text-lg font-semibold">Add User</h2>
                    <a class="text-gray-400 hover:text-gray-300 font-semibold cursor-pointer" @click="closeModal">x</a>
                </div>
                <div class="flex flex-col my-4">
                    <label class="block font-semibold text-gray-600" for="task_name">Fullname</label>
                    <input
                        type="text"
                        v-model="userForm.name"
                        placeholder="Fullname"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <label class="label" v-if="$page.props.errors && $page.props.errors.name">
                        <span class="label-text-alt text-red-600">{{ $page.props.errors.name }}</span>
                    </label>
                </div>
                <div class="flex flex-col my-4">
                    <label class="block font-semibold text-gray-600" for="task_name">Email</label>
                    <input
                        type="text"
                        v-model="userForm.email"
                        placeholder="Email"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <label class="label" v-if="$page.props.errors && $page.props.errors.email">
                        <span class="label-text-alt text-red-600">{{ $page.props.errors.email }}</span>
                    </label>
                </div>
                <div class="flex gap-2 my-2">
                    <div class="w-full">
                        <label class="block font-semibold text-gray-600" for="task_end_date">Select Role</label>
                        <select
                            v-model="userForm.role"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Select Role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.name">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex my-6 gap-2">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <PrimaryButton @click="saveUser">Save</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "Index",
    components: {SecondaryButton, PrimaryButton, AuthenticatedLayout, Head, Link },
    props: {
        users: {
            type: Object,
            required: true,
        },
        roles: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showModal: false,
            userForm: {
                name: "",
                email: "",
                role: "",
            },
        };
    },
    methods: {
        closeModal() {
            this.showModal = false;
        },
        saveUser() {
            this.closeModal()
            router.post(route('users.store', this.userForm));
        },
        deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                router.delete(route('users.destroy', id), {
                    onSuccess: () => {
                        console.log("User deleted successfully!");
                    },
                    onError: () => {
                        console.error("Failed to delete user.");
                    }
                });
            }
        },
    }
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
