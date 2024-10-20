<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import StatusCard from '@/Components/StatusCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Dashboard', href: route('dashboard') },
];

interface DashboardStats {
    totalConnections: number;
    completedBackups: number;
    failedBackups: number;
    totalBackups: number;
    totalRestores: number;
    totalUsers: number;
}

const stats = ref<DashboardStats>({
    totalConnections: 0,
    completedBackups: 0,
    failedBackups: 0,
    totalBackups: 0,
    totalRestores: 0,
    totalUsers: 0,
});

const fetchStats = async (): Promise<void> => {
    try {
        const response = await axios.get<DashboardStats>(
            route('dashboard.stats'),
        );
        stats.value = response.data;
    } catch (error) {
        console.error('Failed to fetch dashboard stats:', error);
    }
};

onMounted(() => {
    fetchStats();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="flex flex-col p-6">
            <h2 class="mb-6 text-2xl font-bold text-white">Dashboard</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <StatusCard
                    title="Total Connections"
                    :value="stats.totalConnections"
                />
                <StatusCard
                    title="Completed Backups"
                    :value="stats.completedBackups"
                />
                <StatusCard
                    title="Failed Backups"
                    :value="stats.failedBackups"
                />
                <StatusCard title="Total Backups" :value="stats.totalBackups" />
                <StatusCard
                    title="Total Restores"
                    :value="stats.totalRestores"
                />
                <StatusCard title="Total Users" :value="stats.totalUsers" />
            </div>

            <div class="mt-8 rounded-lg bg-gray-800 p-6 shadow-md">
                <h3 class="mb-4 text-xl font-semibold text-white">
                    Recent Activity
                </h3>
                <!-- You can add a table or list of recent activities here -->
                <p class="text-gray-400">No recent activities to display.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
