<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

const props = defineProps({
    invoiceCount: Number,
    invoiceTotal: Number,
    monthlyStats: Object,
    onTrial: Boolean,
    canCreateInvoice: Boolean,
    trialEndsAt: String
});

const page = usePage();
const __ = (key, replacements = {}) => {
    let translation = page.props.translations?.[key] || key;
    Object.keys(replacements).forEach(r => {
        translation = translation.replace(`:${r}`, replacements[r]);
    });
    return translation;
};

const chartData = computed(() => ({
  labels: Object.keys(props.monthlyStats),
  datasets: [
    {
      label: __('Monthly Revenue'),
      backgroundColor: '#4F46E5',
      borderColor: '#4F46E5',
      data: Object.values(props.monthlyStats)
    }
  ]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
}
</script>

<template>
    <Head :title="__('Dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Dashboard') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Trial Warning -->
                <div v-if="!canCreateInvoice" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">{{ __('Trial Expired') }}</p>
                    <p>{{ __('Your free trial period has ended. Please subscribe to continue creating invoices.') }}</p>
                    <Link :href="route('subscription.index')" class="underline font-bold mt-2 inline-block">{{ __('Subscribe Now') }}</Link>
                </div>
                
                <div v-else-if="onTrial" class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <p class="font-bold">{{ __('Trial Active') }}</p>
                    <p>{{ __('You are currently on a free trial until :date.', { date: trialEndsAt }) }}</p>
                </div>

                <!-- Metrics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm uppercase font-bold mb-2">{{ __('Total Invoices') }}</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ invoiceCount }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm uppercase font-bold mb-2">{{ __('Total Revenue') }}</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ invoiceTotal.toFixed(2) }}</div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Revenue Overview') }}</h3>
                    <div class="h-64">
                        <Line :data="chartData" :options="chartOptions" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

