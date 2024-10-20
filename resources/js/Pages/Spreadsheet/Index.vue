<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const fileName = ref<string | null>(null);
const selectedTable = ref<string>('');
const importedData = ref<any[]>([]);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        fileName.value = file.name;
    }
};

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Handle Spreadsheets', href: route('spreadsheet.index') },
];

// Available tables for export (dummy data)
const availableTables = ['users', 'products', 'orders'];

// Dummy function to simulate importing data
const importSpreadsheet = () => {
    if (!selectedTable.value || !fileName.value) {
        alert('Please select a table and a file');
        return;
    }
    // Simulating an API call or file processing
    setTimeout(() => {
        importedData.value = [
            { id: 1, name: 'John Doe', email: 'john@example.com' },
            { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
            { id: 3, name: 'Bob Johnson', email: 'bob@example.com' },
        ];
        alert(`Data imported successfully for table: ${selectedTable.value}`);
    }, 1000);
};

// Dummy function to simulate exporting data
const exportSpreadsheet = () => {
    if (!selectedTable.value) {
        alert('Please select a table to export');
        return;
    }
    // Simulating an API call or file generation
    setTimeout(() => {
        alert(`Data exported successfully for table: ${selectedTable.value}`);
    }, 1000);
};
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
            <div class="mb-8 rounded-lg bg-gray-800 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Import Spreadsheet
                </h3>
                <select
                    v-model="selectedTable"
                    class="mb-4 w-1/4 rounded bg-gray-600 p-2 text-white"
                >
                    <option value="">Select a table</option>
                    <option
                        v-for="table in availableTables"
                        :key="table"
                        :value="table"
                    >
                        {{ table }}
                    </option>
                </select>
                <br />
                <input
                    type="file"
                    ref="restoreFileInput"
                    class="hidden"
                    @change="handleFileChange"
                    accept=".csv,.xlsx"
                />
                <label class="mb-5 flex items-center">
                    <SecondaryButton @click="$refs.restoreFileInput.click()">
                        Select File
                    </SecondaryButton>
                    <span class="ml-2 text-white">
                        {{ fileName || 'No file selected' }}
                    </span>
                </label>
                <PrimaryButton @click="importSpreadsheet"
                    >Import Spreadsheet</PrimaryButton
                >
            </div>

            <!-- Export Section -->
            <div class="mb-8 rounded-lg bg-gray-800 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Export Spreadsheet
                </h3>
                <select
                    v-model="selectedTable"
                    class="mb-4 w-1/4 rounded bg-gray-600 p-2 text-white"
                >
                    <option value="">Select a table</option>
                    <option
                        v-for="table in availableTables"
                        :key="table"
                        :value="table"
                    >
                        {{ table }}
                    </option>
                </select>
                <br />
                <PrimaryButton @click="exportSpreadsheet"
                    >Export Spreadsheet</PrimaryButton
                >
            </div>

            <!-- Imported Data Table -->
            <div
                v-if="importedData.length > 0"
                class="rounded-lg bg-gray-800 p-4"
            >
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Imported Data
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead
                            class="bg-gray-600 text-xs uppercase text-gray-200"
                        >
                            <tr>
                                <th
                                    v-for="(value, key) in importedData[0]"
                                    :key="key"
                                    class="px-6 py-3"
                                >
                                    {{ key }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in importedData"
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
