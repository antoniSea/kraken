<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: Array
});

const users = ref([...props.users]);

const editingUser = ref(null);
const showEditModal = ref(false);
const editForm = ref({});

const showAddModal = ref(false);
const addForm = ref({ name: '', email: '', password: '', is_admin: false });

function openEdit(user) {
    editingUser.value = user;
    editForm.value = { ...user };
    showEditModal.value = true;
}
function closeEdit() {
    showEditModal.value = false;
    editingUser.value = null;
}
async function saveEdit() {
    await fetch(`/admin/users/${editingUser.value.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') },
        body: JSON.stringify(editForm.value)
    });
    await refreshUsers();
    closeEdit();
}
async function deleteUser(user) {
    if (confirm(`Czy na pewno chcesz usunąć użytkownika ${user.name}?`)) {
        await fetch(`/admin/users/${user.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }
        });
        await refreshUsers();
    }
}
function openAdd() {
    addForm.value = { name: '', email: '', password: '', is_admin: false };
    showAddModal.value = true;
}
function closeAdd() {
    showAddModal.value = false;
}
async function saveAdd() {
    await fetch('/admin/users', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') },
        body: JSON.stringify(addForm.value)
    });
    await refreshUsers();
    closeAdd();
}
async function refreshUsers() {
    const res = await fetch('/admin');
    const html = await res.text();
    // Parsujemy dane z Inertia (hack, bo nie mamy API):
    const match = html.match(/window\.__INITIAL_PAGE__ = (.*);/);
    if (match) {
        const page = JSON.parse(match[1]);
        users.value = page.props.users;
    }
}
</script>

<template>
    <Head title="Panel administracyjny" />
    <div class="min-h-screen flex flex-col items-center justify-center bg-black bg-opacity-80 font-sans">
        <div class="w-full max-w-3xl p-8 rounded-lg shadow-lg border border-gray-500 bg-black bg-opacity-80 mt-12">
            <h2 class="text-3xl font-bold text-center mb-4 text-white">Panel administracyjny</h2>
            <p class="text-center text-gray-300 mb-8">Zarządzaj użytkownikami serwisu. Tutaj możesz przeglądać, dodawać, edytować i usuwać konta użytkowników.</p>
            <div class="flex justify-center mb-6">
                <button @click="openAdd" class="bg-white text-black font-bold py-2 px-6 rounded hover:bg-gray-200 transition">Dodaj użytkownika</button>
            </div>
            <div class="bg-[#232323] rounded-lg p-6 text-white border border-gray-700">
                <table class="min-w-full text-sm text-white">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Imię i nazwisko</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Admin</th>
                            <th class="px-4 py-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td class="border px-4 py-2">{{ user.id }}</td>
                            <td class="border px-4 py-2">{{ user.name }}</td>
                            <td class="border px-4 py-2">{{ user.email }}</td>
                            <td class="border px-4 py-2 text-center">
                                <span v-if="user.is_admin">✔️</span>
                                <span v-else>—</span>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <button @click="openEdit(user)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded mr-2">Edytuj</button>
                                <button @click="deleteUser(user)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Usuń</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal edycji użytkownika -->
        <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
            <div class="bg-[#232323] p-8 rounded-lg shadow-lg border border-gray-700 w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 text-white">Edytuj użytkownika</h3>
                <form @submit.prevent="saveEdit">
                    <div class="mb-4">
                        <label class="block text-white mb-1">Imię i nazwisko</label>
                        <input v-model="editForm.name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-600" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-white mb-1">Email</label>
                        <input v-model="editForm.email" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-600" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-white mb-1">Admin</label>
                        <input type="checkbox" v-model="editForm.is_admin" />
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeEdit" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded">Anuluj</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Zapisz</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal dodawania użytkownika -->
        <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
            <div class="bg-[#232323] p-8 rounded-lg shadow-lg border border-gray-700 w-full max-w-md">
                <h3 class="text-xl font-bold mb-4 text-white">Dodaj użytkownika</h3>
                <form @submit.prevent="saveAdd">
                    <div class="mb-4">
                        <label class="block text-white mb-1">Imię i nazwisko</label>
                        <input v-model="addForm.name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-600" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-white mb-1">Email</label>
                        <input v-model="addForm.email" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-600" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-white mb-1">Hasło</label>
                        <input v-model="addForm.password" type="password" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-600" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-white mb-1">Admin</label>
                        <input type="checkbox" v-model="addForm.is_admin" />
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeAdd" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded">Anuluj</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Dodaj</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template> 