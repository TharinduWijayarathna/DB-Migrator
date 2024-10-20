<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    collapsed: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:collapsed', value: boolean): void;
}>();

const isCollapsed = ref(props.collapsed);

// Watch for changes in the prop and update the local state
watch(
    () => props.collapsed,
    (newValue) => {
        isCollapsed.value = newValue;
    },
);

const sidebarWidth = computed(() => (isCollapsed.value ? 'w-16' : 'w-64'));

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    emit('update:collapsed', isCollapsed.value);
};

// Use localStorage to persist the sidebar state
onMounted(() => {
    const storedState = localStorage.getItem('sidebarCollapsed');
    if (storedState !== null) {
        isCollapsed.value = JSON.parse(storedState);
        emit('update:collapsed', isCollapsed.value);
    }
});

watch(isCollapsed, (newValue) => {
    localStorage.setItem('sidebarCollapsed', JSON.stringify(newValue));
});

const page = usePage();
</script>

<template>
    <aside
        :class="[
            'bg-dark-navbar text-dark-text transition-all duration-300 ease-in-out',
            sidebarWidth,
        ]"
    >
        <div class="flex items-center justify-between p-5">
            <Link :href="route('dashboard')" v-show="!isCollapsed">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-dark-text"
                />
            </Link>
            <button
                @click="toggleSidebar"
                class="rounded p-1 hover:bg-dark-hover focus:outline-none"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    ></path>
                </svg>
            </button>
        </div>
        <nav>
            <NavLink
                :href="route('dashboard')"
                :active="page.component === 'Dashboard/Index'"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    page.component === 'Dashboard/Index'
                        ? 'bg-dark-secondary text-white'
                        : 'hover:bg-white hover:text-dark-navbar',
                ]"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    ></path>
                </svg>
                <span class="mx-4 pt-1" :class="{ hidden: isCollapsed }"
                    >Dashboard</span
                >
            </NavLink>
            <NavLink
                :href="route('connection.index')"
                :active="page.component === 'DBConnection/Index'"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    page.component === 'DBConnection/Index'
                        ? 'bg-dark-secondary text-white'
                        : 'hover:bg-white hover:text-dark-navbar',
                ]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3c4.418 0 8 1.343 8 3s-3.582 3-8 3-8-1.343-8-3 3.582-3 8-3z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 8.25c0 1.657 3.582 3 8 3s8-1.343 8-3M4 13.5c0 1.657 3.582 3 8 3s8-1.343 8-3M4 18.75c0 1.657 3.582 3 8 3s8-1.343 8-3"
                    />
                </svg>
                <span class="mx-4 pt-1" :class="{ hidden: isCollapsed }">
                    Connect Database
                </span>
            </NavLink>
            <NavLink
                :href="route('query.index')"
                :active="page.component === 'Query/Index'"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    page.component === 'Query/Index'
                        ? 'bg-dark-secondary text-white'
                        : 'hover:bg-white hover:text-dark-navbar',
                ]"
            >
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
                        d="m6.75 7.5 3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z"
                    />
                </svg>

                <span class="mx-4 pt-1" :class="{ hidden: isCollapsed }">
                    Run SQL Query
                </span>
            </NavLink>
            <NavLink
                :href="route('backup_restore.index')"
                :active="page.component === 'BackupAndRestore/Index'"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    page.component === 'BackupAndRestore/Index'
                        ? 'bg-dark-secondary text-white'
                        : 'hover:bg-white hover:text-dark-navbar',
                ]"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path stroke="none" d="M0 0h24v24H0z" />
                    <polyline points="12 8 12 12 14 14" />
                    <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                </svg>

                <span class="mx-4 pt-1" :class="{ hidden: isCollapsed }">
                    Backup & Restore
                </span>
            </NavLink>
        </nav>
    </aside>
</template>
