<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    nextInvoiceNumber: String,
});

const form = useForm({
    issuer_name: '',
    issuer_address: '',
    issuer_tax_id: '',
    client_name: '',
    client_address: '',
    invoice_number: props.nextInvoiceNumber || '',
    issue_date: new Date().toISOString().substr(0, 10),
    due_date: '',
    currency: 'GEL',
    language: 'en',
    items: [
        { description: '', quantity: 1, price: 0 }
    ],
});

const addItem = () => {
    form.items.push({ description: '', quantity: 1, price: 0 });
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

const total = ref(0);

watch(() => form.items, (items) => {
    total.value = items.reduce((acc, item) => acc + (item.quantity * item.price), 0);
}, { deep: true });

const submit = () => {
    form.post(route('invoices.store'));
};
</script>

<template>
    <Head title="Create Invoice" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Invoice</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Issuer Info -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Issuer Info</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Issuer Name</label>
                                    <input v-model="form.issuer_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax ID / CNPJ</label>
                                    <input v-model="form.issuer_tax_id" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Issuer Address</label>
                                <textarea v-model="form.issuer_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            </div>
                        </div>

                        <!-- Client Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client Name</label>
                                <input v-model="form.client_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice Number</label>
                                <input v-model="form.invoice_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client Address</label>
                            <textarea v-model="form.client_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        </div>

                        <!-- Dates & Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Issue Date</label>
                                <input v-model="form.issue_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                <input v-model="form.due_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                                <select v-model="form.currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="GEL">GEL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="RUB">RUB</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Language</label>
                                <select v-model="form.language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="en">English</option>
                                    <option value="ka">Georgian</option>
                                    <option value="ru">Russian</option>
                                </select>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Items</h3>
                            <div class="space-y-4">
                                <div v-for="(item, index) in form.items" :key="index" class="flex gap-4 items-start">
                                    <div class="flex-1">
                                        <input v-model="item.description" type="text" placeholder="Description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                                    </div>
                                    <div class="w-24">
                                        <input v-model="item.quantity" type="number" min="1" placeholder="Qty" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                                    </div>
                                    <div class="w-32">
                                        <input v-model="item.price" type="number" step="0.01" min="0" placeholder="Price" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                                    </div>
                                    <div class="pt-1">
                                        <button @click="removeItem(index)" type="button" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button @click="addItem" type="button" class="mt-4 text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                + Add Item
                            </button>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 flex justify-end">
                            <div class="text-right">
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Total Amount</span>
                                <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ total.toFixed(2) }} {{ form.currency }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <Link :href="route('invoices.index')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</Link>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition shadow-lg" :disabled="form.processing">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
