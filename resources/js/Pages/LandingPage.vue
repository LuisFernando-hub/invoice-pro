<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const locale = computed(() => page.props.locale);
const locales = computed(() => page.props.locales);

const switchLanguage = (lang) => {
    if (lang !== locale.value) {
        router.post(route('language', lang), {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: Force reload if needed, but Inertia should handle it
            }
        });
    }
};
</script>

<template>
    <Head :title="__('Premium Invoicing')" />

    <div class="min-h-screen bg-slate-900 text-white selection:bg-indigo-500 selection:text-white font-sans">
        <!-- Background Gradients -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[120px]"></div>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <header class="container mx-auto px-6 py-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Invoice<span class="text-indigo-400">Pro</span></span>
                </div>

                <nav class="flex items-center gap-6">
                    <!-- Language Switcher -->
                    <div class="flex items-center bg-slate-800/50 rounded-lg p-1 border border-slate-700/50 backdrop-blur-sm">
                        <button 
                            v-for="lang in locales" 
                            :key="lang"
                            @click="switchLanguage(lang)"
                            class="px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 uppercase"
                            :class="locale === lang ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-700/50'"
                        >
                            {{ lang }}
                        </button>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-sm font-medium text-slate-300 hover:text-white transition"
                        >
                            {{ __('Dashboard') }}
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm font-medium text-slate-300 hover:text-white transition"
                            >
                                {{ __('Log in') }}
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-5 py-2 bg-white text-slate-900 rounded-lg text-sm font-semibold hover:bg-indigo-50 transition shadow-lg shadow-white/10"
                            >
                                {{ __('Get Started') }}
                            </Link>
                        </template>
                    </div>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="container mx-auto px-6 pt-20 pb-32 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium mb-8 animate-fade-in-up">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    {{ __('Now available in Georgian, Russian & English') }}
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400">
                    {{ __('Invoicing Made') }} <br class="hidden md:block" />
                    <span class="text-indigo-400">{{ __('Simple & Global') }}</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-12 leading-relaxed">
                    {{ __('Create professional invoices in seconds. Export to PDF & Word. Manage your business finances with ease in your preferred language.') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold text-lg transition shadow-xl shadow-indigo-600/20 flex items-center justify-center gap-2"
                    >
                        {{ __('Start Free Trial') }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                    <a href="#pricing" class="w-full sm:w-auto px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-semibold text-lg transition border border-slate-700">
                        {{ __('View Pricing') }}
                    </a>
                </div>

                <!-- Mockup / Visual -->
                <div class="mt-20 relative mx-auto max-w-5xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent z-10"></div>
                    <div class="rounded-xl bg-slate-800/50 border border-slate-700/50 p-2 shadow-2xl backdrop-blur-sm">
                        <div class="rounded-lg bg-slate-900 aspect-[16/9] flex items-center justify-center text-slate-600">
                            <!-- Placeholder for App Screenshot -->
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 opacity-50">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                </svg>
                                <span class="text-sm font-medium">{{ __('App Dashboard Preview') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Features -->
            <section class="py-24 bg-slate-800/30 border-y border-slate-800">
                <div class="container mx-auto px-6">
                    <div class="grid md:grid-cols-3 gap-12">
                        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/30 transition group">
                            <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('Multi-Language Support') }}</h3>
                            <p class="text-slate-400">{{ __('Seamlessly switch between English, Russian, and Georgian. Perfect for international business.') }}</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/30 transition group">
                            <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('PDF & Word Export') }}</h3>
                            <p class="text-slate-400">{{ __('Download your invoices in professional PDF format or editable Word documents.') }}</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/30 transition group">
                            <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('Easy Duplication') }}</h3>
                            <p class="text-slate-400">{{ __('Save time by duplicating existing invoices. Streamline your billing process.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing -->
            <section id="pricing" class="py-24 container mx-auto px-6">
                <div class="max-w-3xl mx-auto text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('Simple, Transparent Pricing') }}</h2>
                    <p class="text-slate-400">{{ __('No hidden fees. Cancel anytime.') }}</p>
                </div>

                <div class="max-w-md mx-auto bg-slate-800 rounded-2xl border border-slate-700 p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">{{ __('POPULAR') }}</div>
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-medium text-slate-300 mb-2">{{ __('Monthly Plan') }}</h3>
                        <div class="text-5xl font-bold text-white mb-2">10 <span class="text-2xl text-slate-400">GEL</span></div>
                        <span class="text-slate-500">{{ __('per month') }}</span>
                    </div>

                    <ul class="space-y-4 mb-8 text-slate-300">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Unlimited Invoices') }}
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('PDF & Word Export') }}
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Multi-language Support') }}
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Priority Support') }}
                        </li>
                    </ul>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="block w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-center transition"
                    >
                        {{ __('Get Started Now') }}
                    </Link>
                </div>
            </section>

            <!-- Footer -->
            <footer class="py-12 border-t border-slate-800 text-center text-slate-500 text-sm">
                <p>&copy; {{ new Date().getFullYear() }} InvoicePro. {{ __('All rights reserved.') }}</p>
            </footer>
        </div>
    </div>
</template>
