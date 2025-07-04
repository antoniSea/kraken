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

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showAgeGate = ref(false);
const showCookieBanner = ref(false);

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
    if (!localStorage.getItem('cookieConsent')) {
        showCookieBanner.value = true;
    }
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

        <div class="min-h-screen bg-black">
    

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
