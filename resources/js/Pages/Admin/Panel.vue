<template>
  <div class="min-h-screen flex bg-neutral-950 text-white">
    <!-- Sidebar -->
    <aside class="w-64 bg-neutral-900 border-r border-neutral-800 flex flex-col py-8 px-4">
      <h2 class="text-2xl font-bold mb-10 text-center tracking-wide">Panel admina</h2>
      <nav class="flex flex-col gap-2">
        <button :class="navClass('users')" @click="section = 'users'">Użytkownicy</button>
        <button :class="navClass('contests')" @click="section = 'contests'">Konkursy</button>
        <button :class="navClass('files')" @click="section = 'files'">Pliki</button>
      </nav>
    </aside>
    <!-- Main -->
    <main class="flex-1 p-8 overflow-auto">
      <!-- Użytkownicy CRUD -->
      <div v-if="section === 'users'">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-bold">Użytkownicy</h1>
          <button @click="openUserModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">+ Dodaj użytkownika</button>
        </div>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">
          <div v-if="loadingUsers" class="text-center py-8 text-gray-400">Ładowanie...</div>
          <table v-else class="w-full text-left">
            <thead>
              <tr class="border-b border-neutral-800">
                <th class="py-2 px-2">ID</th>
                <th class="py-2 px-2">Imię</th>
                <th class="py-2 px-2">Email</th>
                <th class="py-2 px-2">Nickname</th>
                <th class="py-2 px-2">Akcje</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id" class="border-b border-neutral-800 hover:bg-neutral-800/50">
                <td class="py-2 px-2">{{ user.id }}</td>
                <td class="py-2 px-2">{{ user.name }}</td>
                <td class="py-2 px-2">{{ user.email }}</td>
                <td class="py-2 px-2">{{ user.nickname }}</td>
                <td class="py-2 px-2 flex gap-2">
                  <button @click="openUserModal(user)" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-white text-sm">Edytuj</button>
                  <button @click="deleteUser(user)" class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded text-white text-sm">Usuń</button>
                </td>
              </tr>
              <tr v-if="users.length === 0">
                <td colspan="5" class="text-center py-8 text-gray-400">Brak użytkowników.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Modal użytkownika -->
        <transition name="fade">
          <div v-if="showUserModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
            <div class="bg-neutral-900 rounded-lg p-8 w-full max-w-md shadow-lg relative">
              <button @click="closeUserModal" class="absolute top-2 right-2 text-gray-400 hover:text-white">&times;</button>
              <h2 class="text-xl font-bold mb-4">{{ userForm.id ? 'Edytuj użytkownika' : 'Dodaj użytkownika' }}</h2>
              <form @submit.prevent="saveUser">
                <div class="mb-4">
                  <label class="block mb-1">Imię</label>
                  <input v-model="userForm.name" class="w-full px-3 py-2 rounded bg-neutral-800 border border-neutral-700 text-white" required />
                </div>
                <div class="mb-4">
                  <label class="block mb-1">Email</label>
                  <input v-model="userForm.email" type="email" class="w-full px-3 py-2 rounded bg-neutral-800 border border-neutral-700 text-white" required />
                </div>
                <div class="mb-4">
                  <label class="block mb-1">Nickname</label>
                  <input v-model="userForm.nickname" class="w-full px-3 py-2 rounded bg-neutral-800 border border-neutral-700 text-white" />
                </div>
                <div v-if="userError" class="text-red-400 mb-2">{{ userError }}</div>
                <div class="flex justify-end gap-2 mt-6">
                  <button type="button" @click="closeUserModal" class="px-4 py-2 bg-neutral-700 rounded text-white">Anuluj</button>
                  <button type="submit" :disabled="userLoading" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded text-white font-semibold">
                    <span v-if="userLoading">Zapisz...</span>
                    <span v-else>Zapisz</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </transition>
      </div>
      <!-- Konkursy CRUD (do zaimplementowania) -->
      <div v-else-if="section === 'contests'">
        <h1 class="text-2xl font-bold mb-6">Konkursy</h1>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">(tutaj będzie CRUD konkursów)</div>
      </div>
      <!-- Pliki CRUD (do zaimplementowania) -->
      <div v-else-if="section === 'files'">
        <h1 class="text-2xl font-bold mb-6">Pliki</h1>
        <div class="bg-neutral-900 rounded-lg p-6 shadow">(tutaj będzie zarządzanie plikami)</div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
const section = ref('users');
function navClass(key) {
  return [
    'py-2 px-4 rounded text-left font-medium transition',
    section.value === key ? 'bg-neutral-800 text-white' : 'hover:bg-neutral-800 text-neutral-300'
  ];
}
// Użytkownicy CRUD
const users = ref([]);
const loadingUsers = ref(false);
const showUserModal = ref(false);
const userForm = reactive({ id: null, name: '', email: '', nickname: '' });
const userLoading = ref(false);
const userError = ref('');

function fetchUsers() {
  loadingUsers.value = true;
  axios.get('/admin/users').then(res => {
    users.value = res.data;
  }).catch(() => {
    users.value = [];
  }).finally(() => loadingUsers.value = false);
}
function openUserModal(user = null) {
  userError.value = '';
  if (user) {
    userForm.id = user.id;
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.nickname = user.nickname;
  } else {
    userForm.id = null;
    userForm.name = '';
    userForm.email = '';
    userForm.nickname = '';
  }
  showUserModal.value = true;
}
function closeUserModal() {
  showUserModal.value = false;
}
function saveUser() {
  userLoading.value = true;
  userError.value = '';
  const payload = { name: userForm.name, email: userForm.email, nickname: userForm.nickname };
  if (userForm.id) {
    axios.put(`/admin/users/${userForm.id}`, payload)
      .then(() => { fetchUsers(); closeUserModal(); })
      .catch(e => userError.value = e.response?.data?.message || 'Błąd zapisu')
      .finally(() => userLoading.value = false);
  } else {
    axios.post('/admin/users', payload)
      .then(() => { fetchUsers(); closeUserModal(); })
      .catch(e => userError.value = e.response?.data?.message || 'Błąd zapisu')
      .finally(() => userLoading.value = false);
  }
}
function deleteUser(user) {
  if (!confirm(`Na pewno usunąć użytkownika ${user.name}?`)) return;
  axios.delete(`/admin/users/${user.id}`).then(fetchUsers);
}
onMounted(fetchUsers);
</script>

<style scoped>
body { background: #18181b; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style> 