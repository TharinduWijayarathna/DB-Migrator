<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
    title: string;
    value: number;
}>();
</script>
<template>
    <div class="rounded-lg bg-gray-700 p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">
                    {{ props.title }}
                </h3>
                <p class="mt-2 text-3xl font-bold text-white">
                    {{ props.value }}
                </p>
            </div>
            <div class="text-4xl text-gray-400">
                <slot />
            </div>
        </div>
    </div>
</template>
