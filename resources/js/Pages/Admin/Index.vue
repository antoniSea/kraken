<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    // users: Array // docelowo tu będzie lista użytkowników
});
</script>

<template>
    <Head title="Panel administracyjny" />
    <div class="min-h-screen flex flex-col items-center justify-center bg-black bg-opacity-80 font-sans">
        <div class="w-full max-w-3xl p-8 rounded-lg shadow-lg border border-gray-500 bg-black bg-opacity-80 mt-12">
            <h2 class="text-3xl font-bold text-center mb-4 text-white">Panel administracyjny</h2>
            <p class="text-center text-gray-300 mb-8">Zarządzaj użytkownikami serwisu. Tutaj możesz przeglądać, dodawać, edytować i usuwać konta użytkowników.</p>
            <div class="flex justify-center mb-6">
                <button class="bg-white text-black font-bold py-2 px-6 rounded hover:bg-gray-200 transition">Dodaj użytkownika</button>
            </div>
            <div class="bg-[#232323] rounded-lg p-6 text-white text-center border border-gray-700">
                <span class="text-gray-400">(Lista użytkowników pojawi się tutaj)</span>
            </div>
        </div>
    </div>
</template> 