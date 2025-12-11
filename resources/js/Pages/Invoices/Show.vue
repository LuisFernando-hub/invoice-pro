<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    invoice: Object,
});

const canDelete = computed(() => {
    if (!props.invoice.due_date) return true;
    return new Date(props.invoice.due_date) > new Date();
});
</script>

<template>
    <Head :title="`Invoice #${invoice.invoice_number}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Invoice #{{ invoice.invoice_number }}
                </h2>
                <div class="flex gap-3">
                    <Link
                        v-if="canDelete"
                        :href="route('invoices.destroy', invoice.id)"
                        method="delete"
                        as="button"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition"
                        onclick="return confirm('Are you sure?')"
                    >
                        Delete
                    </Link>
                    <Link
                        :href="route('invoices.duplicate', invoice.id)"
                        method="post"
                        as="button"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    >
                        Duplicate
                    </Link>
                    <a
                        :href="route('invoices.download', { invoice: invoice.id, format: 'pdf' })"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/20"
                        target="_blank"
                    >
                        PDF
                    </a>
                    <a
                        :href="route('invoices.download', { invoice: invoice.id, format: 'word' })"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition shadow-lg shadow-blue-500/20"
                        target="_blank"
                    >
                        Word
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">
                    <div class="p-10">
                        <!-- Header Section -->
                        <div class="flex justify-between items-start mb-12">
                            <div class="flex gap-4">
                                <!-- <div class="w-16 h-16 bg-[#B45309] rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                    Z
                                </div> -->
                                <div>
                                    <!-- <h1 class="text-[#B45309] font-bold text-xl">Zylker Design Labs</h1>
                                    <div class="text-gray-500 text-sm mt-1">
                                        14B, Northern Street<br>
                                        Greater South Avenue<br>
                                        New York 10001<br>
                                        U.S.A
                                    </div> -->
                                </div>
                            </div>
                            <div class="text-right">
                                <h2 class="text-[#B45309] text-4xl font-light uppercase tracking-wide mb-2">INVOICE</h2>
                                <div class="font-bold text-gray-800"># {{ invoice.invoice_number }}</div>
                                <div class="mt-4">
                                    <div class="text-sm text-gray-600">Balance Due</div>
                                    <div class="text-xl font-bold text-gray-900">{{ invoice.total_amount }} {{ invoice.currency }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Addresses & Dates -->
                        <div class="flex justify-between mb-12">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Bill To</div>
                                <div class="text-[#B45309] font-bold text-lg">{{ invoice.client_name }}</div>
                                <div class="text-gray-500 text-sm whitespace-pre-line mt-1">{{ invoice.client_address }}</div>
                            </div>
                            <div class="text-right space-y-2">
                                <div class="flex justify-end gap-8">
                                    <span class="text-gray-600">Invoice Date:</span>
                                    <span class="font-medium text-gray-900">{{ invoice.issue_date }}</span>
                                </div>
                                <div class="flex justify-end gap-8">
                                    <span class="text-gray-600">Terms:</span>
                                    <span class="font-medium text-gray-900">Due on Receipt</span>
                                </div>
                                <div class="flex justify-end gap-8" v-if="invoice.due_date">
                                    <span class="text-gray-600">Due Date:</span>
                                    <span class="font-medium text-gray-900">{{ invoice.due_date }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-8">
                            <table class="w-full">
                                <thead class="bg-[#B45309] text-white">
                                    <tr>
                                        <th class="py-2 px-4 text-left font-medium">#</th>
                                        <th class="py-2 px-4 text-left font-medium">Item & Description</th>
                                        <th class="py-2 px-4 text-right font-medium">Qty</th>
                                        <th class="py-2 px-4 text-right font-medium">Rate</th>
                                        <th class="py-2 px-4 text-right font-medium">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="(item, index) in invoice.items" :key="index">
                                        <td class="py-4 px-4 text-gray-600">{{ index + 1 }}</td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium text-gray-900">{{ item.description }}</div>
                                            <!-- <div class="text-sm text-gray-500">Description here if needed</div> -->
                                        </td>
                                        <td class="py-4 px-4 text-right text-gray-600">{{ parseFloat(item.quantity).toFixed(2) }}</td>
                                        <td class="py-4 px-4 text-right text-gray-600">{{ parseFloat(item.price).toFixed(2) }}</td>
                                        <td class="py-4 px-4 text-right font-medium text-gray-900">{{ (item.quantity * item.price).toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer / Totals -->
                        <div class="flex justify-between items-end">
                            <div class="w-1/2">
                                <div class="mb-6">
                                    <h3 class="font-medium text-gray-900 mb-2">Notes</h3>
                                    <p class="text-gray-500 text-sm">Thanks for your business.</p>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Terms & Conditions</h3>
                                    <p class="text-gray-500 text-sm">All payments must be made in full before the commencement of any design work.</p>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <div class="flex justify-between py-2 text-gray-600">
                                    <span>Sub Total</span>
                                    <span>{{ invoice.total_amount }}</span>
                                </div>
                                <div class="flex justify-between py-2 text-gray-600">
                                    <span>Tax Rate</span>
                                    <span>0.00%</span>
                                </div>
                                <div class="flex justify-between py-2 font-bold text-gray-900 text-lg mt-2">
                                    <span>Total</span>
                                    <span>{{ invoice.currency }} {{ invoice.total_amount }}</span>
                                </div>
                                <div class="bg-[#B45309] text-white flex justify-between p-3 mt-4 font-bold text-lg rounded-sm">
                                    <span>Balance Due</span>
                                    <span>{{ invoice.currency }} {{ invoice.total_amount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
