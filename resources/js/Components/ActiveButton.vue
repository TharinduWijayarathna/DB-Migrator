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
                ? 'cursor-not-allowed border-transparent bg-gray-400 text-gray-700'
                : 'bg-green-600 text-white hover:bg-green-500 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:bg-green-700',
        ]"
    >
        <slot />
    </button>
</template>
