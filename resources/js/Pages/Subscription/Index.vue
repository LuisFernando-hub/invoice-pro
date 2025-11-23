<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    subscriptionEndsAt: String,
    isSubscribed: Boolean,
});

const form = useForm({});

const subscribe = () => {
    form.post(route('subscription.store'));
};
</script>

<template>
    <Head title="Subscription" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                    <div v-if="isSubscribed" class="text-center">
                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">You are subscribed!</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Your subscription is active until {{ subscriptionEndsAt }}.</p>
                    </div>

                    <div v-else class="max-w-md mx-auto text-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Upgrade to Premium</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-8">Get unlimited access to all features for just 10 GEL/month.</p>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                            <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">10 GEL</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">per month</div>
                        </div>

                        <form @submit.prevent="subscribe">
                            <button 
                                type="submit" 
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition shadow-lg shadow-indigo-500/20"
                                :disabled="form.processing"
                            >
                                Subscribe Now
                            </button>
                        </form>
                        <p class="text-xs text-gray-400 mt-4">This is a mock payment for demonstration purposes.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
