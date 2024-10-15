<script setup lang="ts">
import ActiveButton from '@/Components/ActiveButton.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import InactiveButton from '@/Components/InactiveButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const breadcrumbItems = [
    { name: 'Connect Database', href: route('connection.index') },
];

const isModalOpen = ref(false);

const form = useForm({
    username: '',
    host: '',
    port: '',
    database: '',
    password: '',
});

const connections = ref([]);

const isDisabled = ref(false);

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const getConnections = async () => {
    try {
        const response = await axios.get(route('connection.all'));
        connections.value = response.data.data;
    } catch (error) {
        console.error(error);
    }
};

const submit = async () => {
    try {
        await axios.post(route('connection.store'), form.data());
        closeModal();
        getConnections();
    } catch (error) {
        form.setErrors(error.response.data.errors);
    }
};

const activate = async (connection) => {
    freezeActions();
    try {
        await axios.patch(route('connection.activate', connection.id));
        await getConnections();
        unfreezeActions();
    } catch (error) {
        console.error(error);
        unfreezeActions();
    }
};

const deactivate = async (connection) => {
    try {
        await axios.patch(route('connection.deactivate', connection.id));
        getConnections();
    } catch (error) {
        console.error(error);
    }
};

const freezeActions = () => {
    isDisabled.value = true; // Disable the buttons
};

const unfreezeActions = () => {
    isDisabled.value = false; // Enable the buttons
};

onMounted(() => {
    getConnections();
});
</script>
<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="py-5 text-end sm:px-6 lg:px-8">
            <PrimaryButton @click="openModal"> Add Connection </PrimaryButton>
        </div>

        <div class="mx-auto sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-white p-5 shadow-sm sm:rounded-lg sm:px-6"
            >
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="bg-gray-50 px-2 py-3 text-left text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                            >
                                Username
                            </th>
                            <th
                                class="bg-gray-50 px-2 py-3 text-left text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                            >
                                Host
                            </th>
                            <th
                                class="bg-gray-50 px-2 py-3 text-left text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                            >
                                Port
                            </th>
                            <th
                                class="bg-gray-50 px-2 py-3 text-left text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                            >
                                Database
                            </th>
                            <th
                                class="bg-gray-50 px-2 py-3 text-left text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"
                            >
                                Password
                            </th>
                            <th class="bg-gray-50 px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr
                            v-for="connection in connections"
                            :key="connection.id"
                        >
                            <td class="whitespace-no-wrap px-2 py-4">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ connection.username }}
                                </div>
                            </td>
                            <td class="whitespace-no-wrap px-2 py-4">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ connection.host }}
                                </div>
                            </td>
                            <td class="whitespace-no-wrap px-2 py-4">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ connection.port }}
                                </div>
                            </td>
                            <td class="whitespace-no-wrap px-2 py-4">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ connection.database }}
                                </div>
                            </td>
                            <td class="whitespace-no-wrap px-2 py-4">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ connection.password }}
                                </div>
                            </td>
                            <td
                                class="whitespace-no-wrap px-2 py-4 text-right text-sm font-medium leading-5"
                            >
                                <ActiveButton
                                    id="activate"
                                    :disabled="isDisabled"
                                    @click="activate(connection)"
                                    v-if="connection.is_active == 0"
                                >
                                    <!-- Activate icon -->
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </ActiveButton>

                                <InactiveButton
                                    id="inactivate"
                                    :disabled="isDisabled"
                                    @click="deactivate(connection)"
                                    v-if="connection.is_active == 1"
                                >
                                    <!-- Deactivate icon -->
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </InactiveButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div
                    class="flex items-center justify-between border-t border-gray-200 bg-white py-3"
                >
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a
                            href="#"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Previous
                        </a>
                        <a
                            href="#"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Next
                        </a>
                    </div>
                    <div
                        class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="pl-1 text-sm text-gray-700">
                                Showing
                                <span class="font-medium">1</span>
                                to
                                <span class="font-medium">10</span>
                                of
                                <span class="font-medium">97</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav
                                class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                aria-label="Pagination"
                            >
                                <a
                                    href="#"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                >
                                    <span class="sr-only">Previous</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    aria-current="page"
                                    class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    1
                                </a>
                                <a
                                    href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                >
                                    2
                                </a>
                                <a
                                    href="#"
                                    class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex"
                                >
                                    3
                                </a>
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0"
                                >
                                    ...
                                </span>
                                <a
                                    href="#"
                                    class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex"
                                >
                                    8
                                </a>
                                <a
                                    href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                >
                                    9
                                </a>
                                <a
                                    href="#"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                >
                                    10
                                </a>
                                <a
                                    href="#"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                >
                                    <span class="sr-only">Next</span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="isModalOpen" @close="closeModal">
            <form @submit.prevent="submit">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Create New Connection
                    </h2>

                    <div class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="username" value="Username" />
                            <TextInput
                                id="username"
                                v-model="form.username"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />
                            <InputError
                                :message="form.errors.username"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <InputLabel for="host" value="Host" />
                            <TextInput
                                id="host"
                                v-model="form.host"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.host"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <InputLabel for="port" value="Port" />
                            <TextInput
                                id="port"
                                v-model="form.port"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.port"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <InputLabel for="database" value="Database" />
                            <TextInput
                                id="database"
                                v-model="form.database"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.database"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                :message="form.errors.password"
                                class="mt-2"
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end bg-gray-50 p-6 text-right"
                >
                    <SecondaryButton @click="closeModal" class="mr-2"
                        >Cancel</SecondaryButton
                    >
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Create Connection
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>
