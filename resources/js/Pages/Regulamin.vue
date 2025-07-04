<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh]">
      <div class="max-w-2xl w-full mx-auto flex flex-col items-center">
        <h1 class="font-brandon-grotesque-black text-5xl md:text-6xl text-white mb-10 mt-16 text-center leading-tight">Regulamin</h1>
        <div class="text-gray-200 text-lg md:text-xl font-medium text-center leading-relaxed mb-12 max-w-2xl mx-auto">
          <p>To jest przykładowy regulamin. Możesz tu umieścić dowolną treść, np. zasady korzystania z serwisu, politykę prywatności, warunki uczestnictwa itp.</p>
          <p class="mt-8">1. Użytkownik zobowiązuje się do przestrzegania zasad.</p>
          <p class="mt-4">2. Wszelkie naruszenia będą skutkować blokadą konta.</p>
          <p class="mt-4">3. Serwis zastrzega sobie prawo do zmian w regulaminie.</p>
        </div>
      </div>
    </div>
  </MainLayout>
</template> 