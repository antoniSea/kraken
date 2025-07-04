<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
  <MainLayout>
    <!-- Dashboard content only, no nav or background here -->
    <section class="w-full flex flex-col items-center mt-8 px-2">
      <div class="max-w-xl w-full bg-transparent border border-gray-500 rounded-lg px-4 py-6 flex flex-col items-center text-center shadow-lg" style="font-family: 'Brandon Grotesque', sans-serif;">
        <h1 class="font-brandon-grotesque-black text-xl md:text-2xl text-white mb-4">The League of Darkness</h1>
        <p class="font-brandon-grotesque-medium text-sm md:text-base text-gray-400 mb-6">
          To zamknięta inicjatywa skierowana do wybranych członków społeczności barmańskiej – tych, którzy czują, że za perfekcyjnie podanym koktajlem, precyzyjnym ruchem ręki i uśmiechem
          w półmroku, kryje się coś więcej. Coś głębszego. Ciemniejszego.
        </p>
        <div class="w-full flex justify-center mb-6">
          <div class="w-24 h-0.5 bg-gray-500 rounded-full" />
        </div>
        <h2 class="font-brandon-grotesque-black text-lg md:text-xl text-white mb-1 leading-tight">
          To nie szkolenie. To rytuał przebudzenia.<br />
          Wielu tworzy koktajle. Nieliczni tworzą kultową ciemność.
        </h2>
      </div>
    </section>
    <section class="w-full flex flex-col items-center mt-10 px-2">
      <h2 class="font-brandon-grotesque-black text-3xl md:text-4xl text-white mb-2 text-center" style="font-family: 'Brandon Grotesque', sans-serif;">Wejdź.</h2>
      <p class="font-brandon-grotesque-black text-lg md:text-xl text-white text-center" style="font-family: 'Brandon Grotesque', sans-serif;">
        Ale nie oczekuj, że wyjdziesz taki sam.
      </p>
    </section>
  </MainLayout>
</template>
