<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import CookieBanner from '@/Components/CookieBanner.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showAgeGate = ref(false);
const showCookieBanner = ref(false);
const showAccountChoice = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

function handleAcceptAge() {
    localStorage.setItem('ageGateAccepted', '1');
    showAgeGate.value = false;
    showAccountChoice.value = true;
}
function handleDeclineAge() {
    showAgeGate.value = false;
    window.location.href = 'https://google.com';
}
function handleAcceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    showCookieBanner.value = false;
}
function handleDeclineCookies() {
    localStorage.setItem('cookieConsent', 'declined');
    showCookieBanner.value = false;
}
function handleAccountChoice(choice) {
    showAccountChoice.value = false;
    if (choice === 'register') {
        window.location.href = '/register';
    } else if (choice === 'login') {
        window.location.href = '/login';
    }
}

onMounted(() => {
    if (!localStorage.getItem('ageGateAccepted')) {
        showAgeGate.value = true;
    } else if (!localStorage.getItem('cookieConsent')) {
        showCookieBanner.value = true;
    }
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner v-if="$page.props.auth && $page.props.auth.user && $page.url !== '/'" />

        <CookieBanner v-if="showCookieBanner" @accept="handleAcceptCookies" @decline="handleDeclineCookies" />
        <ConfirmationModal :show="showAgeGate" :closeable="false">
            <template #title>
                Potwierdzenie wieku
            </template>
            <template #content>
                <div class="text-white">Czy masz ukończone 18 lat?</div>
            </template>
            <template #footer>
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" @click="handleAcceptAge">Tak</button>
                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="handleDeclineAge">Nie</button>
            </template>
        </ConfirmationModal>
        <ConfirmationModal :show="showAccountChoice" :closeable="false">
            <template #title>
                Masz już konto?
            </template>
            <template #content>
                <div class="text-white">Wybierz jedną z opcji poniżej:</div>
            </template>
            <template #footer>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" @click="handleAccountChoice('register')">Rejestracja</button>
                <button class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" @click="handleAccountChoice('login')">Login</button>
            </template>
        </ConfirmationModal>

        <div class="min-h-screen bg-black">
    

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
