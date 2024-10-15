<script setup lang="ts">
import { computed } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const props = defineProps<{
    items: BreadcrumbItem[];
}>();

const breadcrumbItems = computed(() => [
    { name: 'Home', href: '/' },
    ...props.items,
]);
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex rounded-md px-6">
            <li
                v-for="(item, index) in breadcrumbItems"
                :key="item.name"
                class="flex"
            >
                <div class="flex items-center">
                    <a
                        :href="item.href"
                        :class="[
                            index === breadcrumbItems.length - 1
                                ? 'text-gray-500'
                                : 'text-gray-400 hover:text-gray-500',
                            index === 0 ? '' : 'ml-1',
                        ]"
                        :aria-current="
                            index === breadcrumbItems.length - 1
                                ? 'page'
                                : undefined
                        "
                    >
                        <span v-if="index === 0" class="sr-only">Home</span>
                        <svg
                            v-if="index === 0"
                            class="h-5 w-5 flex-shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span v-else class="text-sm font-medium">{{
                            item.name
                        }}</span>
                    </a>
                </div>
                <div
                    v-if="index < breadcrumbItems.length - 1"
                    class="flex items-center"
                >
                    <svg
                        class="h-5 w-5 flex-shrink-0 text-gray-300"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </li>
        </ol>
    </nav>
</template>
