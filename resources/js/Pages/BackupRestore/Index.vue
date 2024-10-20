<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Backup & Restore', href: route('backup_restore.index') },
];

const backupType = ref('full');
const includeData = ref(true);
const selectedTables = ref([]);
const tables = ref([]);
const restoreFileInput = ref<HTMLInputElement | null>(null);
const fileName = ref('');
const restoreFile = ref<File | null>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        if (file.name.toLowerCase().endsWith('.sql')) {
            restoreFile.value = file;
            fileName.value = file.name;
        } else {
            console.error('Please select a valid .sql file');
            resetFileInput();
        }
    } else {
        resetFileInput();
    }
};

const resetFileInput = () => {
    if (restoreFileInput.value) {
        restoreFileInput.value.value = '';
    }
    restoreFile.value = null;
    fileName.value = '';
};

const handleBackup = () => {
    try {
        if (backupType.value === 'full') {
            const url = includeData.value
                ? route('backup_restore.full_backup')
                : route('backup_restore.full_backup_no_data');

            axios
                .get(url)
                .then((response) => {
                    downloadBackup(response.data.file);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        } else {
            if (selectedTables.value.length > 0) {
                const url = includeData.value
                    ? route('backup_restore.selective_backup')
                    : route('backup_restore.selective_backup_no_data');

                axios
                    .get(url, {
                        params: {
                            tables: selectedTables.value,
                        },
                    })
                    .then((response) => {
                        downloadBackup(response.data.file);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            } else {
                console.log('Please select tables to backup');
            }
        }
    } catch (error) {
        console.error('Error backing up database:', error);
    }
};

const downloadBackup = (file: string) => {
    fetch('/storage/' + file)
        .then((response) => response.blob())
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = file.split('/').pop() || 'backup.sql';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            // Delete the file after download
            axios
                .delete(route('backup_restore.delete_backup'), {
                    params: { file: file },
                })
                .catch((error) => {
                    console.error('Error deleting backup file:', error);
                });
        })
        .catch((error) => {
            console.error('Error downloading file:', error);
        });
};

const handleRestore = () => {
    try {
        if (!restoreFile.value) {
            console.error('No file selected or invalid file');
            return;
        }

        const formData = new FormData();
        formData.append('file', restoreFile.value);

        axios
            .post(route('backup_restore.restore'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then((response) => {
                console.log('Database restored successfully', response.data);
                resetFileInput();
                // You might want to show a success message to the user here
            })
            .catch((error) => {
                console.error(
                    'Error restoring database:',
                    error.response?.data || error,
                );
                // You might want to show an error message to the user here
            });
    } catch (error) {
        console.error('Error in handleRestore:', error);
    }
};

const getDBTableNames = async () => {
    try {
        const response = await axios.get(route('backup_restore.tables'));
        tables.value = response.data.tables;
    } catch (error) {
        console.error('Error fetching table names:', error);
        return [];
    }
};

onMounted(() => {
    getDBTableNames();
});
</script>

<template>
    <Head title="Database Management" />

    <AuthenticatedLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>
        <div class="flex flex-col p-6">
            <h2 class="mb-5 text-xl font-medium text-white">
                Backup & Restore
            </h2>
            <div class="grid grid-cols-2 space-x-8">
                <div
                    class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-white">
                        <div class="">
                            <h3 class="mb-4 text-xl font-medium">
                                Backup Database
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block"
                                        >Backup Type:</label
                                    >
                                    <select
                                        v-model="backupType"
                                        class="w-full rounded bg-gray-700 p-2"
                                    >
                                        <option value="full">
                                            Full Backup
                                        </option>
                                        <option value="selected">
                                            Selected Tables
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <Checkbox
                                            :checked="includeData"
                                            @update:checked="
                                                includeData = $event
                                            "
                                        />
                                        <span class="ms-2 text-sm text-white"
                                            >Include Data</span
                                        >
                                    </label>
                                </div>
                                <div v-if="backupType === 'selected'">
                                    <label class="mb-2 block"
                                        >Select Tables:</label
                                    >
                                    <div class="space-y-2">
                                        <label
                                            v-for="table in tables"
                                            :key="table"
                                            class="flex items-center"
                                        >
                                            <Checkbox
                                                :checked="
                                                    selectedTables.includes(
                                                        table,
                                                    )
                                                "
                                                @update:checked="
                                                    selectedTables = $event
                                                        ? [
                                                              ...selectedTables,
                                                              table,
                                                          ]
                                                        : selectedTables.filter(
                                                              (t) =>
                                                                  t !== table,
                                                          )
                                                "
                                            />
                                            <span
                                                class="ms-2 text-sm text-white"
                                                >{{ table }}</span
                                            >
                                        </label>
                                    </div>
                                </div>
                                <PrimaryButton @click="handleBackup">
                                    Backup Database
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-white">
                        <div>
                            <h3 class="text-xl font-medium">
                                Restore Database
                            </h3>
                            <div class="space-y-4">
                                <div class="mb-10">
                                    <label class="mb-4 mt-3 block"
                                        >Select Backup File (.SQL):</label
                                    >
                                    <input
                                        type="file"
                                        ref="restoreFileInput"
                                        class="hidden"
                                        @change="handleFileChange"
                                        accept=".sql"
                                    />
                                    <label class="flex items-center">
                                        <SecondaryButton
                                            @click="
                                                $refs.restoreFileInput.click()
                                            "
                                        >
                                            Select File
                                        </SecondaryButton>
                                        <span class="ml-2">
                                            {{ fileName || 'No file selected' }}
                                        </span>
                                    </label>
                                </div>
                                <PrimaryButton @click="handleRestore">
                                    Restore Database
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
