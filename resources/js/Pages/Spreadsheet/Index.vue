<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Handle Spreadsheets', href: route('spreadsheet.index') },
];

// Dummy data for table display
const dummyData = [
    { id: 1, name: 'John Doe', email: 'john@example.com' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
    { id: 3, name: 'Bob Johnson', email: 'bob@example.com' },
];

// Available tables for export (dummy data)
const availableTables = ['users', 'products', 'orders'];
</script>

<template>
    <Head title="Handle Spreadsheets" />

    <AuthenticatedLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="flex flex-col p-6">
            <h2 class="mb-6 text-2xl font-bold text-white">
                Handle Spreadsheets
            </h2>

            <!-- Import Section -->
            <div class="mb-8 rounded-lg bg-gray-700 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Import Excel
                </h3>
                <input
                    type="file"
                    accept=".xlsx,.xls"
                    class="mb-4 text-white"
                />
                <button
                    class="rounded bg-blue-500 px-4 py-2 text-white transition-colors hover:bg-blue-600"
                >
                    Import
                </button>
            </div>

            <!-- Export Section -->
            <div class="mb-8 rounded-lg bg-gray-700 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Export Excel
                </h3>
                <select class="mb-4 rounded bg-gray-600 p-2 text-white">
                    <option value="">Select a table</option>
                    <option
                        v-for="table in availableTables"
                        :key="table"
                        :value="table"
                    >
                        {{ table }}
                    </option>
                </select>
                <button
                    class="rounded bg-green-500 px-4 py-2 text-white transition-colors hover:bg-green-600"
                >
                    Export
                </button>
            </div>

            <!-- Dummy Data Table -->
            <div class="rounded-lg bg-gray-700 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Imported Data (Example)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead
                            class="bg-gray-600 text-xs uppercase text-gray-200"
                        >
                            <tr>
                                <th
                                    v-for="(value, key) in dummyData[0]"
                                    :key="key"
                                    class="px-6 py-3"
                                >
                                    {{ key }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in dummyData"
                                :key="row.id"
                                class="border-b border-gray-700 bg-gray-800"
                            >
                                <td
                                    v-for="(value, key) in row"
                                    :key="key"
                                    class="px-6 py-4"
                                >
                                    {{ value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
