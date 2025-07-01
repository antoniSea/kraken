<template>
  <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-[95vw] max-w-3xl bg-[#222] border-2 border-blue-400 rounded-xl flex items-center px-6 py-4 shadow-lg">
    <span class="mr-4 text-2xl">🍪</span>
    <span class="flex-1 text-xs text-gray-200">
      Używamy plików cookie, aby poprawić jakość przeglądania, wyświetlać reklamy lub treści dostosowane do indywidualnych potrzeb użytkownika oraz analizować ruch na stronie. „Akceptuj wszystkie” oznacza zgodę na wykorzystywanie cookies.
    </span>
    <button @click="$emit('decline')" class="ml-4 px-6 py-2 border border-gray-400 text-white rounded bg-[#222] hover:bg-gray-700">Odrzuć</button>
    <button @click="$emit('accept')" class="ml-2 px-6 py-2 border border-gray-400 text-white rounded bg-[#222] hover:bg-blue-700">Akceptuj</button>
  </div>
</template>

<script setup>
</script> 