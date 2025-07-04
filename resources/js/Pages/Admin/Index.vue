<script setup>
import { ref, onMounted } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import axios from 'axios';

const users = ref([]);

onMounted(async () => {
  const res = await axios.get('/admin/users-files');
  users.value = res.data;
});
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh]">
      <div class="max-w-5xl w-full mx-auto flex flex-col items-center">
        <h1 class="font-brandon-grotesque-black text-4xl md:text-5xl text-white mb-10 mt-16 text-center leading-tight">Panel administratora</h1>
        <table class="w-full text-left text-white bg-neutral-900 rounded-lg overflow-hidden">
          <thead>
            <tr class="bg-neutral-800">
              <th class="px-4 py-2">Użytkownik</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Konkurs</th>
              <th class="px-4 py-2">Pliki</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b border-neutral-700">
              <td class="px-4 py-2">{{ user.name }}</td>
              <td class="px-4 py-2">{{ user.email }}</td>
              <td class="px-4 py-2">{{ user.konkurs?.name || '-' }}</td>
              <td class="px-4 py-2">
                <ul>
                  <li v-for="plik in user.pliki" :key="plik.id">
                    <a :href="'/storage/' + plik.path" target="_blank" class="underline text-blue-400 hover:text-blue-200">{{ plik.original_name }}</a>
                  </li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </MainLayout>
</template> 