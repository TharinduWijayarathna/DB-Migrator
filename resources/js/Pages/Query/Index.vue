<script setup lang="ts">
import Breadcrumb from '@/Components/Breadcrumb.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';

interface BreadcrumbItem {
    name: string;
    href: string;
}

const breadcrumbItems: BreadcrumbItem[] = [
    { name: 'Run SQL Query', href: route('query.index') },
];

const sqlQuery = ref('');
const queryOutput = ref('');
const syntaxError = ref('');
const errorMessage = ref('');

const runQuery = async () => {
    errorMessage.value = ''; // Clear any previous errors
    const response = await axios.post(route('query.run'), {
        query: sqlQuery.value,
    });
    if (response.data.success) {
        queryOutput.value = JSON.stringify(response.data.results, null, 2);
    } else {
        errorMessage.value = response.data.error;
    }
};

const checkSyntax = (query: string) => {
    const trimmedQuery = query.trim().toUpperCase();
    const basicSQLKeywords = [
        'SELECT',
        'INSERT',
        'UPDATE',
        'DELETE',
        'CREATE',
        'ALTER',
        'DROP',
        'TRUNCATE',
    ];
    const words = trimmedQuery.split(/\s+/);

    if (words.length === 0) {
        syntaxError.value = 'Query cannot be empty.';
        return;
    }

    if (!basicSQLKeywords.includes(words[0])) {
        syntaxError.value =
            'Query should start with a valid SQL keyword (SELECT, INSERT, UPDATE, DELETE, etc.)';
        return;
    }

    // Check for basic structure based on the first keyword
    switch (words[0]) {
        case 'SELECT':
            if (!trimmedQuery.includes('FROM')) {
                syntaxError.value =
                    'SELECT query should include a FROM clause.';
                return;
            }
            break;
        case 'INSERT':
            if (!trimmedQuery.includes('INTO')) {
                syntaxError.value =
                    'INSERT query should include an INTO clause.';
                return;
            }
            break;
        case 'UPDATE':
            if (!trimmedQuery.includes('SET')) {
                syntaxError.value = 'UPDATE query should include a SET clause.';
                return;
            }
            break;
        case 'DELETE':
            if (!trimmedQuery.includes('FROM')) {
                syntaxError.value =
                    'DELETE query should include a FROM clause.';
                return;
            }
            break;
    }

    // Check for unmatched parentheses
    const openParenCount = (trimmedQuery.match(/\(/g) || []).length;
    const closeParenCount = (trimmedQuery.match(/\)/g) || []).length;
    if (openParenCount !== closeParenCount) {
        syntaxError.value = 'Unmatched parentheses in the query.';
        return;
    }

    // Additional validations
    if (
        trimmedQuery.includes('DROP TABLE') ||
        trimmedQuery.includes('TRUNCATE TABLE')
    ) {
        syntaxError.value =
            'Warning: This query will delete data. Please double-check before running.';
        return;
    }

    if (trimmedQuery.includes('SELECT *') && !trimmedQuery.includes('LIMIT')) {
        syntaxError.value =
            'Warning: SELECT * without LIMIT might return a large dataset. Consider adding a LIMIT clause.';
        return;
    }

    // Check for common SQL injection patterns
    const injectionPatterns = ['--', '1=1', "' OR '1'='1", '" OR "1"="1'];
    if (injectionPatterns.some((pattern) => trimmedQuery.includes(pattern))) {
        syntaxError.value =
            'Warning: Potential SQL injection detected. Please review your query.';
        return;
    }

    // If we've made it this far, clear any existing error
    syntaxError.value = '';
};

watch(sqlQuery, (newQuery) => {
    checkSyntax(newQuery);
});
</script>

<template>
    <Head title="Query" />

    <AuthenticatedLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="flex flex-col p-6">
            <h2 class="mb-5 text-xl font-medium text-white">SQL Query</h2>

            <div class="relative mb-4">
                <textarea
                    v-model="sqlQuery"
                    class="h-60 w-full resize-y rounded-md border border-gray-700 bg-gray-800 p-2 font-mono text-sm font-bold text-gray-200 focus:border-indigo-500 focus:outline-none"
                    placeholder="Enter your SQL query here..."
                ></textarea>
                <p v-if="syntaxError" class="mt-2 text-sm text-red-400">
                    {{ syntaxError }}
                </p>
            </div>

            <button
                @click="runQuery"
                :disabled="!!syntaxError"
                class="rounded-md bg-indigo-600 px-4 py-2 text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Run Query
            </button>

            <div v-if="queryOutput" class="mt-6">
                <h3 class="mb-2 text-lg font-medium text-white">
                    Query Output:
                </h3>
                <div v-if="errorMessage" class="error-message">
                    {{ errorMessage }}
                </div>
                <pre
                    class="overflow-x-auto rounded-md bg-gray-700 p-4 text-gray-200"
                    >{{ queryOutput }}</pre
                >
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom scrollbar styles for webkit browsers */
textarea::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}

textarea::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 6px;
}

textarea::-webkit-scrollbar-thumb {
    background-color: #4b5563;
    border-radius: 6px;
    border: 3px solid #374151;
}

textarea::-webkit-scrollbar-thumb:hover {
    background-color: #6b7280;
}
</style>
