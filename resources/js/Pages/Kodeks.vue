<script setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

const slides = [
  {
    title: 'Głos',
    content: [
      'Używaj słów oszczędnie. Ale niech zapadają głęboko.'
    ]
  },
  {
    title: 'Ruch',
    content: [
      'Każdy gest to rytuał. Szkło. Butelka. Lód. Rytm ma znaczenie. Nie jesteś dilerem przyjemności. Każdy klient jest intencją, a Ty tworzysz doświadczenie.',
      'Kontekst. Trunek to nośnik – a twoją misją jest przekaz.'
    ]
  },
  {
    title: 'Smak',
    content: [
      'Twórz nie dla kubków smakowych, lecz dla pamięci. Gość nie przychodzi po drinka. Przychodzi po doświadczenie.',
      'Nie improwizujesz. Nawet chaos ma swoją recepturę.',
      'Znasz składniki, które wypływają z otchłani.',
      'Nie mieszaj Kraken z hałasem. Serwuj w ciszy, bez słów.'
    ]
  },
  {
    title: 'Cień',
    content: [
      'Nie pokazuj wszystkiego. Tajemnica jest paliwem. Pamiętaj momenty.',
      'Ilość nie tworzy legendy. Głębia – tak. Lód nie pęka przypadkiem.'
    ]
  },
  {
    title: 'Obecność',
    content: [
      'Nie bądź w centrum. Bądź jak cień przy świecy – nie do pominięcia, ale nieuchwytny.',
      'Nie pytaj, co podać. Obserwuj. Wybierz. Zaskocz. Dobrze dobrany alkohol mówi więcej niż słowa. Źle dobrany – mówi o tobie.'
    ]
  },
  {
    title: 'Rytuał',
    content: [
      'Miej własne rytuały. Ukryte gesty. Stałe słowa. Powtarzaj je przed każdą zmianą.',
      'Nie mieszasz składników – mieszasz rzeczywistość.'
    ]
  },
  {
    title: 'Symbole',
    content: [
      'Symbole ukrywasz na wierzchu.',
      'Układ butelek. Porządek etykiet. Kształt szkła.'
    ]
  }
];

const current = ref(0);
const direction = ref('right');

function prev() {
  direction.value = 'left';
  current.value = (current.value - 1 + slides.length) % slides.length;
}
function next() {
  direction.value = 'right';
  current.value = (current.value + 1) % slides.length;
}
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh] px-2">
      <div class="w-full max-w-none px-0 sm:max-w-xl sm:px-6 mx-auto flex flex-col items-center relative rounded-lg md:px-10 py-4 sm:py-6 shadow-lg">
        <button @click="prev" class="absolute left-0 sm:-left-8 top-1/2 -translate-y-1/2 rounded-full border border-white/30 bg-transparent hover:bg-white/10 text-white p-2 transition shadow-sm focus:outline-none flex items-center justify-center h-10 w-10 z-10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <div class="flex-1 flex flex-col items-center justify-center px-0 sm:px-2 w-full min-h-[220px] sm:min-h-[340px]">
          <transition :name="direction === 'right' ? 'slide-right' : 'slide-left'" mode="out-in">
            <div :key="current" class="w-full">
              <h1 class="font-brandon-grotesque-black text-2xl sm:text-3xl md:text-4xl text-white mb-4 sm:mb-6 text-center leading-tight">{{ slides[current].title }}</h1>
              <div class="text-gray-200 text-base sm:text-lg font-medium text-center leading-relaxed mb-4 sm:mb-6 max-w-xs mx-auto">
                <template v-for="(p, i) in slides[current].content" :key="i">
                  <p class="mb-3 sm:mb-4 whitespace-pre-line">{{ p }}</p>
                </template>
              </div>
            </div>
          </transition>
        </div>
        <button @click="next" class="absolute right-0 sm:-right-8 top-1/2 -translate-y-1/2 rounded-full border border-white/30 bg-transparent hover:bg-white/10 text-white p-2 transition shadow-sm focus:outline-none flex items-center justify-center h-10 w-10 z-10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
        <div class="flex items-center justify-center gap-2 mt-2 w-full">
          <span v-for="(s, i) in slides" :key="i" :class="['inline-block h-2 w-2 rounded-full transition-all', i === current ? 'bg-white' : 'bg-gray-500 opacity-50']"></span>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<style scoped>
.slide-right-enter-active, .slide-right-leave-active,
.slide-left-enter-active, .slide-left-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: absolute;
  width: 100%;
}
.slide-right-enter-from {
  opacity: 0;
  transform: translateX(60px);
}
.slide-right-leave-to {
  opacity: 0;
  transform: translateX(-60px);
}
.slide-left-enter-from {
  opacity: 0;
  transform: translateX(-60px);
}
.slide-left-leave-to {
  opacity: 0;
  transform: translateX(60px);
}
</style> 