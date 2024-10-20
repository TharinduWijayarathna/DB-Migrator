<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-medium text-indigo-300 bg-indigo-700 focus:outline-none focus:text-indigo-100 focus:bg-indigo-800 focus:border-indigo-400 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-600 hover:border-gray-500 focus:outline-none focus:text-gray-200 focus:bg-gray-600 focus:border-gray-500 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
