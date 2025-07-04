<script setup>
import { ref, computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

// Carousel logic
const icons = [
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
  'data:image/svg+xml;utf8,<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="14" fill="%23222222" fill-opacity="0.7" stroke="%23fff" stroke-width="2"/></svg>',
];
const visibleCount = 4;
const startIndex = ref(0);
const isSliding = ref(false);
const slideDirection = ref('');

function prev() {
  if (isSliding.value) return;
  slideDirection.value = 'left';
  isSliding.value = true;
  setTimeout(() => {
    startIndex.value = (startIndex.value - 1 + icons.length) % icons.length;
    isSliding.value = false;
  }, 300);
}
function next() {
  if (isSliding.value) return;
  slideDirection.value = 'right';
  isSliding.value = true;
  setTimeout(() => {
    startIndex.value = (startIndex.value + 1) % icons.length;
    isSliding.value = false;
  }, 300);
}
const visibleIcons = computed(() => {
  const arr = [];
  for (let i = 0; i < visibleCount; i++) {
    arr.push(icons[(startIndex.value + i) % icons.length]);
  }
  return arr;
});
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh]">
      <div class="max-w-2xl w-full mx-auto flex flex-col items-center">
        <h1 class="font-brandon-grotesque-black text-5xl md:text-6xl text-white mb-10 mt-16 text-center leading-tight">Smak</h1>
        <div class="text-gray-200 text-lg md:text-2xl font-medium text-center leading-relaxed mb-12 max-w-2xl mx-auto">
          <p>Twórz nie dla kubków smakowych, lecz dla pamięci. Gość nie przychodzi<br>po drinka. Przychodzi po doświadczenie.</p>
          <p class="mt-8">Nie improwizujesz. Nawet chaos ma swoją recepturę.<br>Znasz składniki, które wypływają z otchłani.</p>
          <p class="mt-8">Nie mieszaj Kraken z hałasem. Serwuj w ciszy, bez słów.</p>
        </div>
        <!-- Carousel with side arrows -->
        <div class="flex items-center justify-center w-full max-w-xs mx-auto mt-2 mb-2 gap-2 relative">
          <button @click="prev" :disabled="isSliding" class="absolute left-[-48px] top-1/2 -translate-y-1/2 rounded-full border border-white/30 bg-transparent hover:bg-white/10 text-white p-2 transition shadow-sm focus:outline-none flex items-center justify-center h-10 w-10 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </button>
          <div class="flex flex-row gap-4 overflow-hidden relative w-[180px] h-10 mx-auto">
            <transition-group name="slide" tag="div" class="flex flex-row gap-4 w-full h-full">
              <img v-for="(icon, idx) in visibleIcons" :key="icon + startIndex" :src="icon" class="h-8 w-8 rounded-full border border-white/20 bg-white/5 object-contain transition-transform duration-300" :class="isSliding ? (slideDirection === 'left' ? 'animate-slide-left' : 'animate-slide-right') : ''" />
            </transition-group>
          </div>
          <button @click="next" :disabled="isSliding" class="absolute right-[-48px] top-1/2 -translate-y-1/2 rounded-full border border-white/30 bg-transparent hover:bg-white/10 text-white p-2 transition shadow-sm focus:outline-none flex items-center justify-center h-10 w-10 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
          </button>
        </div>
        <div class="flex items-center justify-center gap-2 mt-4">
          <span v-for="n in icons.length" :key="n" :class="[ 'inline-block h-2 w-2 rounded-full', n-1 === startIndex ? 'bg-white' : 'bg-gray-500 opacity-50']"></span>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<style scoped>
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s;
}
.slide-enter-from {
  transform: translateX(100%);
}
.slide-leave-to {
  transform: translateX(-100%);
}
.slide-leave-active {
  position: absolute;
}
</style> 