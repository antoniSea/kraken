<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[60vh]">
      <div class="max-w-xl w-full mx-auto flex flex-col items-center">
        <h1 class="font-brandon-grotesque-black text-2xl md:text-3xl text-white mb-6 mt-10 text-center leading-tight">Regulamin</h1>
        <div class="text-gray-200 text-sm md:text-base font-medium text-center leading-relaxed mb-8 max-w-xl mx-auto">
          <p>To jest przykładowy regulamin. Możesz tu umieścić dowolną treść, np. zasady korzystania z serwisu, politykę prywatności, warunki uczestnictwa itp.</p>
          <p class="mt-6">1. Użytkownik zobowiązuje się do przestrzegania zasad.</p>
          <p class="mt-3">2. Wszelkie naruszenia będą skutkować blokadą konta.</p>
          <p class="mt-3">3. Serwis zastrzega sobie prawo do zmian w regulaminie.</p>
        </div>
      </div>
    </div>
  </MainLayout>
</template> 