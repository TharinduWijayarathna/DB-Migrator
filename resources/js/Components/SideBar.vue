<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    collapsed: boolean;
}>();

const emit = defineEmits<{
    (e: 'toggle'): void;
}>();

const sidebarWidth = computed(() => (props.collapsed ? 'w-16' : 'w-64'));

const toggleSidebar = () => {
    emit('toggle');
};
</script>

<template>
    <aside
        :class="[
            'bg-dark-navbar text-dark-text transition-all duration-300 ease-in-out',
            sidebarWidth,
        ]"
    >
        <div class="flex items-center justify-between p-5">
            <Link :href="route('dashboard')" v-show="!collapsed">
                <ApplicationLogo
                    class="text-dark-text block h-9 w-auto fill-current"
                />
            </Link>
            <button
                @click="toggleSidebar"
                class="hover:bg-dark-hover rounded p-1 focus:outline-none"
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
                :active="route().current('dashboard')"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    route().current('dashboard')
                        ? 'bg-dark-secondary'
                        : 'hover:bg-white',
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
                <span class="mx-4 pt-1" v-show="!collapsed">Dashboard</span>
            </NavLink>
            <NavLink
                :href="route('connection.index')"
                :active="route().current('connection.index')"
                class="flex w-full items-center px-6 py-3 pt-3 text-white transition-colors duration-200"
                :class="[
                    route().current('connection.index')
                        ? 'bg-dark-secondary'
                        : 'hover:bg-white',
                ]"
            >
                <!-- New Database SVG Icon -->
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
                <span class="mx-4 pt-1" v-show="!collapsed">
                    Connect Database
                </span>
            </NavLink>
        </nav>
    </aside>
</template>
