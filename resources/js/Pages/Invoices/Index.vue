<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    invoices: Array,
});
</script>

<template>
    <Head title="Invoices" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Invoices</h2>
                <Link
                    :href="route('invoices.create')"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition shadow-lg shadow-indigo-500/20"
                >
                    Create Invoice
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div v-if="invoices.length === 0" class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-4 opacity-50">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 class="text-lg font-medium mb-2">No invoices yet</h3>
                        <p class="mb-6">Create your first invoice to get started.</p>
                        <Link
                            :href="route('invoices.create')"
                            class="text-indigo-600 hover:text-indigo-500 font-medium"
                        >
                            Create Invoice &rarr;
                        </Link>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-medium">Number</th>
                                    <th class="px-6 py-4 font-medium">Client</th>
                                    <th class="px-6 py-4 font-medium">Date</th>
                                    <th class="px-6 py-4 font-medium">Amount</th>
                                    <th class="px-6 py-4 font-medium">Status</th>
                                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ invoice.invoice_number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ invoice.client_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ invoice.issue_date }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ invoice.total_amount }} {{ invoice.currency }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': invoice.status === 'paid',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': invoice.status === 'sent',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': invoice.status === 'draft'
                                            }"
                                        >
                                            {{ invoice.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <Link :href="route('invoices.show', invoice.id)" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                View
                                            </Link>
                                            <Link :href="route('invoices.duplicate', invoice.id)" method="post" as="button" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                Duplicate
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
