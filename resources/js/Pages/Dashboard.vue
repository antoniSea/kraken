<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Strona w budowie">
        <div class="min-h-[60vh] flex flex-col items-center justify-center">
            <div class="bg-black bg-opacity-80 p-12 rounded-lg shadow-lg border border-gray-500 max-w-xl w-full text-center">
                <h1 class="text-4xl font-bold text-white mb-6">Strona w budowie</h1>
                <p class="text-lg text-gray-300 mb-4">Panel użytkownika jest w trakcie realizacji.<br>Wkrótce pojawią się tu nowe funkcje!</p>
                <div class="mt-8">
                    <svg class="mx-auto animate-bounce" width="48" height="48" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M12 2a1 1 0 0 1 1 1v14.586l4.293-4.293a1 1 0 1 1 1.414 1.414l-6 6a1 1 0 0 1-1.414 0l-6-6A1 1 0 0 1 5.707 13.293L10 17.586V3a1 1 0 0 1 1-1Z"/></svg>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
