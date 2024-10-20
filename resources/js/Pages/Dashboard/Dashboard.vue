<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Dashboard', href: route('dashboard') },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="flex">
            <div class="flex-1">
                <div
                    class="mt-6 flex items-center justify-between sm:rounded-lg sm:px-6 lg:px-8"
                >
                    <h2 class="mb-5 text-lg font-medium text-white">
                        Dashboard
                    </h2>
                </div>
            </div>
        </div>

        <div class="mx-auto sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-gray-800 p-5 shadow-sm sm:rounded-lg sm:px-6"
            ></div>
        </div>
    </AuthenticatedLayout>
</template>
