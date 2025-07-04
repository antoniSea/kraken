<script setup>
import { ref, onMounted } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import axios from 'axios';

const files = ref([]);
const uploading = ref(false);
const success = ref(false);
const error = ref('');
const konkursy = ref([]);
const konkursId = ref(null);
const activeIdx = ref(null);

onMounted(async () => {
  const res = await axios.get('/profile/konkursy');
  konkursy.value = res.data;
  if (konkursy.value.length) konkursId.value = konkursy.value[0].id;
});

function handleFiles(event) {
  files.value = Array.from(event.target.files);
}
function removeFile(idx) {
  files.value.splice(idx, 1);
}
async function uploadFiles() {
  if (!files.value.length) return;
  uploading.value = true;
  error.value = '';
  const form = new FormData();
  files.value.forEach(f => form.append('files[]', f));
  form.append('konkurs_id', konkursId.value);
  try {
    await axios.post('/profile/files', form, { headers: { 'Content-Type': 'multipart/form-data' } });
    success.value = true;
    files.value = [];
  } catch (e) {
    if (e.response && e.response.data && e.response.data.message && e.response.data.message.includes('konkurs')) {
      error.value = 'Brak aktywnego konkursu. Skontaktuj się z administratorem.';
    } else {
      error.value = 'Błąd podczas wysyłania plików.';
    }
  }
  uploading.value = false;
}
function fileIcon(file) {
  if (file.type.includes('pdf')) return 'pdf';
  if (file.type.includes('image')) return 'img';
  if (file.type.includes('video') || file.name.endsWith('.mov')) return 'mov';
  return 'file';
}
</script>

<template>
  <MainLayout>
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh]">
      <div class="max-w-2xl w-full mx-auto flex flex-col items-center">
        <h1 class="font-brandon-grotesque-black text-3xl md:text-4xl text-white mb-8 mt-12 text-center leading-tight">Profil</h1>
        <!-- Wybór konkursu -->
        <div v-if="konkursy.length" class="w-full flex justify-center mb-6">
          <select v-model="konkursId" class="bg-neutral-900 border border-neutral-700 text-white rounded px-4 py-2 text-lg">
            <option v-for="k in konkursy" :key="k.id" :value="k.id">{{ k.name }}</option>
          </select>
        </div>
        <!-- Sekcja uploadu plików -->
        <div class="w-full flex flex-col items-center mt-4">
          <label class="block w-full cursor-pointer">
            <input type="file" multiple class="hidden" @change="handleFiles" />
            <div class="border-2 border-dashed border-gray-400 rounded-md h-40 flex flex-col items-center justify-center text-gray-400 bg-neutral-900/90 hover:bg-neutral-900/80 transition mb-4 relative">
              <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              <span class="text-base font-bold text-white"><span class="underline">Kliknij i dodaj plik</span> lub przeciąg go tutaj.</span>
              <span class="text-xs mt-2">Maksymalny rozmiar pliku do 10MB.</span>
            </div>
          </label>
          <div v-if="files.length" class="w-full mb-4">
            <ul class="space-y-2">
              <li v-for="(file, idx) in files" :key="file.name"
                  class="flex items-center justify-between border bg-neutral-950 rounded px-4 py-2 transition-all"
                  :class="[activeIdx === idx ? 'border-blue-500 ring-2 ring-blue-500' : 'border-neutral-800', 'hover:border-blue-500 hover:ring-2 hover:ring-blue-500']"
                  @mouseenter="activeIdx = idx" @mouseleave="activeIdx = null">
                <div class="flex items-center gap-2">
                  <svg v-if="fileIcon(file) === 'pdf'" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 2v4a2 2 0 0 0 2 2h4"/></svg>
                  <svg v-else-if="fileIcon(file) === 'img'" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                  <svg v-else-if="fileIcon(file) === 'mov'" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M10 9l5 3-5 3V9z"/></svg>
                  <svg v-else class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                  <span class="text-white truncate max-w-[180px]">{{ file.name }}</span>
                </div>
                <button @click="removeFile(idx)" class="text-gray-400 hover:text-red-400 ml-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </li>
            </ul>
          </div>
          <button @click="uploadFiles" :disabled="uploading || !files.length" class="border-2 border-solid border-neutral-500 bg-neutral-900 px-16 py-3 rounded text-white font-brandon-grotesque-medium text-lg disabled:opacity-50 mt-4">
            <span v-if="!uploading">Wyślij</span>
            <span v-else>Wysyłanie...</span>
          </button>
          <div v-if="success" class="text-green-400 mt-4">Dziękujemy za wysłanie plików!</div>
          <div v-if="error" class="text-red-400 mt-4">{{ error }}</div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
