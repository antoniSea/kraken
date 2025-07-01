<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    policy: String,
});
</script>

<template>
    <Head title="Polityka prywatności" />

    <div class="min-h-screen flex flex-col items-center justify-center bg-black bg-opacity-80 font-sans">
        <div class="w-full max-w-2xl p-8 rounded-lg shadow-lg border border-gray-500 bg-black bg-opacity-80 mt-12">
            <div class="flex flex-col items-center mb-8">
                <h2 class="text-3xl font-bold text-center mb-4 text-white mt-4">Polityka prywatności</h2>
            </div>
            <div class="prose prose-invert text-white max-w-none" v-html="policy" />
        </div>
    </div>
</template>
