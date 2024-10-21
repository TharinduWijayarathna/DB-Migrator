<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, reactive, onMounted } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const fileName = ref<string | null>(null);
const selectedExportTable = ref<string>('');
const selectedImportTable = ref<string>('');
const importedData = ref<any[]>([]);
const selectedFile = ref<File | null>(null); // To store the uploaded file

const notification = reactive({
    type: '',
    message: '',
    visible: false,
});

const showNotification = (type: string, message: string) => {
    notification.type = type;
    notification.message = message;
    notification.visible = true;
    setTimeout(() => {
        notification.visible = false;
    }, 3000); // Hide after 3 seconds
};

const dismissNotification = () => {
    notification.visible = false;
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        selectedFile.value = target.files[0]; // Save the file
        fileName.value = selectedFile.value.name; // Save the file name
    }
};

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Handle Spreadsheets', href: route('spreadsheet.index') },
];

const availableTables = ref<string[]>([]);

const importSpreadsheet = () => {
    if (!selectedImportTable.value || !selectedFile.value) {
        showNotification('error', 'Please select a table and a file');
        return;
    }

    // FormData to handle file uploads
    const formData = new FormData();
    formData.append('file', selectedFile.value as Blob); // Append file
    formData.append('table', selectedImportTable.value);

    axios
        .post(route('spreadsheet.import'), formData)
        .then((response) => {
            importedData.value = response.data;
            fileName.value = null; // Reset the file name
            selectedFile.value = null; // Reset the file
            showNotification('success', 'Data imported successfully');
        })
        .catch(() => {
            showNotification('error', 'Failed to import data');
        });
};

const exportSpreadsheet = () => {
    if (!selectedExportTable.value) {
        showNotification('error', 'Please select a table to export');
        return;
    }

    // Export request with table name
    axios
        .get(
            route('spreadsheet.export', { table: selectedExportTable.value }),
            {
                responseType: 'blob',
            },
        )
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${selectedExportTable.value}.xlsx`);
            document.body.appendChild(link);
            link.click();
            showNotification('success', 'Data exported successfully');
        })
        .catch(() => {
            showNotification('error', 'Failed to export data');
        });
};

const getDBTableNames = async () => {
    try {
        const response = await axios.get(route('backup_restore.tables'));
        availableTables.value = response.data.tables;
    } catch (error) {
        console.error('Error fetching table names:', error);
        showNotification('error', 'Failed to fetch table names');
    }
};

onMounted(() => {
    getDBTableNames();
});
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

            <div
                v-if="notification.visible"
                :class="[
                    'relative mb-4 rounded-md p-4 shadow-md',
                    notification.type === 'success'
                        ? 'bg-green-600'
                        : 'bg-red-600',
                ]"
            >
                <button
                    @click="dismissNotification"
                    class="absolute right-2 top-2 text-white hover:text-gray-200"
                >
                    &#x2715;
                </button>
                <p class="font-semibold text-white">
                    {{ notification.type === 'success' ? 'Success' : 'Error' }}
                </p>
                <p class="mt-1 text-white">{{ notification.message }}</p>
            </div>

            <!-- Import Section -->
            <div class="mb-8 rounded-lg bg-gray-800 p-4">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Import Spreadsheet
                </h3>
                <select
                    v-model="selectedImportTable"
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
                    v-model="selectedExportTable"
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
