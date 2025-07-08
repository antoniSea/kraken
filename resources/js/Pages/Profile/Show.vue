<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import MainLayout from '@/Layouts/MainLayout.vue';

const files = ref([]);
const uploading = ref(false);
const success = ref(false);
const error = ref('');
const konkursy = ref([]);
const konkursId = ref(null);
const activeIdx = ref(null);
const showConfetti = ref(false);

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
    showConfetti.value = true;
    setTimeout(() => { showConfetti.value = false; }, 1800);
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
    <div class="relative min-h-screen w-full flex flex-col items-center justify-center before:absolute before:inset-0 before:-z-10 before:bg-gradient-to-b before:from-black before:to-neutral-900 before:opacity-80 before:content-['']">
      <!-- Header zunifikowany -->
    
      <!-- Zakładki konkursów - nad boksem bio -->
      <div v-if="konkursy.length" class="w-full flex flex-wrap justify-center gap-3.5 text-center text-sm text-neutral-500 font-poppins mb-0 z-20">
        <template v-for="k in konkursy" :key="k.id">
          <button
            @click="konkursId = k.id"
            class="flex items-center justify-center px-10 py-2.5 transition-all duration-150"
            :class="[
              konkursId === k.id
                ? 'bg-neutral-400 text-white'
                : 'bg-neutral-800 text-neutral-500',
            ]"
            style="outline: none; border: none; border-radius: 0; min-width: 120px; font-weight: 500;"
          >
            <span class="text-center w-full">{{ k.name }}</span>
          </button>
        </template>
      </div>
      <!-- Boks bio -->
      <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-solid border-neutral-800 bg-neutral-900/90 text-[gray] backdrop-blur-md shadow-xl px-6 pt-0 pb-10 max-w-2xl w-full mx-4 relative min-h-[480px]">
        <!-- Sekcja uploadu plików -->
        <div class="w-full flex flex-col items-center">
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
          <transition name="fade">
            <div v-if="showConfetti" class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
              <div class="flex flex-col items-center">
                <svg class="w-24 h-24 text-green-400 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#22c55e"/>
                  <path stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-5" />
                </svg>
                <span class="text-2xl text-green-300 font-bold mt-4 animate-fade-in">Plik wysłany!</span>
              </div>
            </div>
          </transition>
          <div v-if="success && !showConfetti" class="text-green-400 mt-4">Dziękujemy za wysłanie plików!</div>
          <div v-if="error" class="text-red-400 mt-4">{{ error }}</div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
.animate-fade-in { animation: fade-in 0.8s; }
</style>
