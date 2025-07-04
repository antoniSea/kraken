<template>
  <div class="min-h-screen flex bg-neutral-950 text-white">
    <!-- Sidebar -->
    <aside class="w-64 bg-neutral-900 border-r border-neutral-800 flex flex-col py-8 px-4">
      <h2 class="text-2xl font-bold mb-10 text-center tracking-wide">Panel admina</h2>
      <nav class="flex flex-col gap-2">
        <button :class="navClass('users')" @click="section = 'users'">Użytkownicy</button>
        <button :class="navClass('contests')" @click="section = 'contests'">Konkursy</button>
        <button :class="navClass('files')" @click="section = 'files'">Pliki</button>
      </nav>
    </aside>
    <!-- Main -->
    <main class="flex-1 p-8 overflow-auto">
      <div v-if="section === 'users'">
        <h1 class="text-2xl font-bold mb-6">Użytkownicy</h1>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">(tutaj będzie CRUD użytkowników)</div>
      </div>
      <div v-else-if="section === 'contests'">
        <h1 class="text-2xl font-bold mb-6">Konkursy</h1>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">(tutaj będzie CRUD konkursów)</div>
      </div>
      <div v-else-if="section === 'files'">
        <h1 class="text-2xl font-bold mb-6">Pliki</h1>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">(tutaj będzie zarządzanie plikami)</div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const section = ref('users');
function navClass(key) {
  return [
    'py-2 px-4 rounded text-left font-medium transition',
    section.value === key ? 'bg-neutral-800 text-white' : 'hover:bg-neutral-800 text-neutral-300'
  ];
}
</script>

<style scoped>
body { background: #18181b; }
</style> 