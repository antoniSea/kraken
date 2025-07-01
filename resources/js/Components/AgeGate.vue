<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80">
    <div class="border border-gray-400 rounded-lg bg-black p-10 max-w-lg w-full text-center relative">
      <h2 class="text-4xl font-bold mb-6 text-white">Witaj!</h2>
      <p class="mb-6 text-white">Strona, którą próbujesz odwiedzić, zawiera treści dotyczące napojów alkoholowych.<br> Zgodnie z obowiązującymi przepisami prawa, dostęp do tych treści mogą uzyskać wyłącznie osoby pełnoletnie.</p>
      <div class="text-2xl font-bold mb-8 text-white">Czy masz ukończone 18 lat?</div>
      <div class="flex justify-center gap-6 mb-6">
        <button @click="accept" class="px-8 py-3 bg-black border border-gray-400 text-white rounded hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-white">Tak, mam ukończone 18 lat</button>
        <button @click="decline" class="px-8 py-3 bg-black border border-gray-400 text-white rounded hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-white">Nie, nie mam</button>
      </div>
      <a href="/privacy-policy" class="underline text-xs text-gray-300 hover:text-white">Polityka prywatności</a>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
  show: Boolean,
});
const emit = defineEmits(['accept', 'decline']);

function accept() {
  emit('accept');
}
function decline() {
  emit('decline');
}
</script> 