<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :disabled="disabled"
        :class="[
            'inline-flex items-center rounded-md border px-2 py-1 text-xs font-semibold uppercase tracking-widest transition duration-150 ease-in-out',
            disabled
                ? 'cursor-not-allowed border-transparent bg-gray-600 text-gray-300'
                : 'bg-red-700 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 active:bg-red-800',
        ]"
    >
        <slot />
    </button>
</template>
