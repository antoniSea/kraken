<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Terms of Service" />
    <div class="min-h-screen flex flex-col items-center justify-center bg-black font-brandon-grotesque text-white">
        <div class="w-full max-w-xl p-8 rounded-lg shadow-lg border border-gray-500 bg-black mt-12 flex flex-col items-center">
            <div class="mb-8 w-full flex flex-col items-center">
                <h2 class="font-brandon-grotesque-black text-3xl md:text-4xl text-white mb-4 mt-4 text-center leading-tight">Terms of Service</h2>
            </div>
            <div class="text-gray-200 text-base md:text-lg font-medium text-center leading-relaxed max-w-xl mx-auto prose prose-invert" v-html="terms" />
        </div>
    </div>
</template>
