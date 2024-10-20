<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SuccessButton from '@/Components/SuccessButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Dashboard', href: route('dashboard') },
    { name: 'Backup & Restore', href: route('backup_restore.index') },
];

const backupType = ref('full');
const includeData = ref(true);
const selectedTables = ref([]);
const tables = ref(['users', 'posts', 'comments', 'categories']); // Example tables, replace with actual tables
const restoreFile = ref({} as File);
const fileName = ref('');

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        restoreFile.value = target.files[0];
    }

    fileName.value = restoreFile.value.name;
};

const handleBackup = () => {
    // Implement backup logic here
    console.log('Backup initiated:', {
        backupType: backupType.value,
        includeData: includeData.value,
        selectedTables: selectedTables.value,
    });
};

const handleRestore = () => {
    // Implement restore logic here
    console.log('Restore initiated with file:', restoreFile.value);
};
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
            <div class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <!-- Backup Section -->
                    <div class="mb-8">
                        <h3 class="mb-4 text-xl font-medium">
                            Backup Database
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block">Backup Type:</label>
                                <select
                                    v-model="backupType"
                                    class="w-full rounded bg-gray-700 p-2"
                                >
                                    <option value="full">Full Backup</option>
                                    <option value="selected">
                                        Selected Tables
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <Checkbox
                                        :checked="includeData"
                                        @update:checked="includeData = $event"
                                    />
                                    <span class="ms-2 text-sm text-white"
                                        >Include Data</span
                                    >
                                </label>
                            </div>
                            <div v-if="backupType === 'selected'">
                                <label class="mb-2 block">Select Tables:</label>
                                <div class="space-y-2">
                                    <label
                                        v-for="table in tables"
                                        :key="table"
                                        class="flex items-center"
                                    >
                                        <input
                                            type="checkbox"
                                            v-model="selectedTables"
                                            :value="table"
                                            class="mr-2"
                                        />
                                        {{ table }}
                                    </label>
                                </div>
                            </div>
                            <PrimaryButton @click="handleBackup">
                                Backup Database
                            </PrimaryButton>
                        </div>
                    </div>

                    <!-- Restore Section -->
                    <div>
                        <h3 class="mb-4 text-xl font-medium">
                            Restore Database
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block"
                                    >Select .sql File:</label
                                >
                                <input
                                    type="file"
                                    ref="restoreFile"
                                    class="hidden"
                                    @change="handleFileChange"
                                />
                                <label class="flex items-center">
                                    <SecondaryButton
                                        @click="$refs.restoreFile.click()"
                                    >
                                        Select File
                                    </SecondaryButton>
                                    <span class="ml-2">
                                        {{
                                            fileName
                                                ? fileName
                                                : 'No file selected'
                                        }}
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
    </AuthenticatedLayout>
</template>
