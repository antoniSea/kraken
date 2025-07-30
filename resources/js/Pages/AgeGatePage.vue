<template>
  <div class="my-16 intro-page" >
    <div class="flex-1 flex flex-col justify-center items-center w-full">
      <div class="relative  rounded-xl max-w-4xl w-full px-12 py-12 shadow-xl"  style=" border: 4px double #fff; ">
        <div class="text-center">
          <div class="text-white font-bold font-brandon-grotesque-black text-4xl md:text-5xl mb-8">Witaj!</div>
          <div class="text-white text-base md:text-lg mb-8">
            Strona, którą próbujesz odwiedzić, zawiera treści dotyczące napojów alkoholowych. 
            Zgodnie z obowiązującymi przepisami prawa, dostęp do tych treści mogą uzyskać wyłącznie osoby pełnoletnie.
          </div>
          <div class="text-white font-bold font-brandon-grotesque-black text-2xl md:text-3xl mb-8">Czy masz ukończone 18 lat?</div>
          <div class="flex flex-row justify-center gap-8 mb-6">
            <button @click="accept" class="w-64 py-3 border border-[#666] rounded  text-white text-base font-brandon-grotesque-medium hover:bg-[#232323] transition">Tak, mam ukończone 18 lat</button>
            <button @click="decline" class="w-64 py-3 border border-[#666] rounded  text-white text-base font-brandon-grotesque-medium hover:bg-[#232323] transition">Nie, nie mam</button>
          </div>
          <a href="/privacy-policy" class="block text-xs text-[#bcbcbc] underline hover:text-white">Polityka prywatności</a>
        </div>
      </div>
    </div>
    
    <!-- Modal po zatwierdzeniu wieku -->
    <div v-if="showAccountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-[#1a1a1a] rounded-xl max-w-md w-full mx-4 px-8 py-8 shadow-xl border border-[#333]">
        <div class="text-center">
          <div class="text-white font-bold font-brandon-grotesque-black text-2xl md:text-3xl mb-6">Masz już konto?</div>
          <div class="flex flex-col gap-4 mb-6">
            <button @click="goToLogin" class="w-full py-3 border border-[#666] rounded text-white text-base font-brandon-grotesque-medium hover:bg-[#232323] transition">
              Login
            </button>
            <button @click="goToRegister" class="w-full py-3 border border-[#666] rounded text-white text-base font-brandon-grotesque-medium hover:bg-[#232323] transition">
              Rejestracja
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <CookieBanner v-if="showCookieBanner" @accept="handleAcceptCookies" @decline="handleDeclineCookies" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import CookieBanner from '@/Components/CookieBanner.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });

const showCookieBanner = ref(false);
const showAccountModal = ref(false);

function handleAcceptCookies() {
  localStorage.setItem('cookieConsent', 'accepted');
  showCookieBanner.value = false;
}
function handleDeclineCookies() {
  localStorage.setItem('cookieConsent', 'declined');
  showCookieBanner.value = false;
}

onMounted(() => {
  if (!localStorage.getItem('cookieConsent')) {
    showCookieBanner.value = true;
  }
});

function accept() {
  localStorage.setItem('ageGateAccepted', '1');
  document.cookie = 'ageGateAccepted=1; path=/; max-age=' + 60*60*24*365;
  showAccountModal.value = true;
}

function decline() {
  window.location.href = 'https://google.com';
}

function goToLogin() {
  window.location.href = '/login';
}

function goToRegister() {
  window.location.href = '/register';
}
</script>

<style scoped>
@font-face {
  font-family: 'Brandon Grotesque';
  src: url('/fonts/BrandonGrotesque-Regular.woff2') format('woff2'),
       url('/fonts/BrandonGrotesque-Regular.woff') format('woff');
  font-weight: 400;
  font-style: normal;
}
@font-face {
  font-family: 'Brandon Grotesque Black';
  src: url('/fonts/BrandonGrotesque-Black.woff2') format('woff2'),
       url('/fonts/BrandonGrotesque-Black.woff') format('woff');
  font-weight: 900;
  font-style: normal;
}
@font-face {
  font-family: 'Brandon Grotesque Medium';
  src: url('/fonts/BrandonGrotesque-Medium.woff2') format('woff2'),
       url('/fonts/BrandonGrotesque-Medium.woff') format('woff');
  font-weight: 500;
  font-style: normal;
}
@font-face {
  font-family: 'Brandon Grotesque Light';
  src: url('/fonts/BrandonGrotesque-Light.woff2') format('woff2'),
       url('/fonts/BrandonGrotesque-Light.woff') format('woff');
  font-weight: 300;
  font-style: normal;
}
.font-brandon-grotesque { font-family: 'Brandon Grotesque', sans-serif; }
.font-brandon-grotesque-black { font-family: 'Brandon Grotesque Black', sans-serif; }
.font-brandon-grotesque-medium { font-family: 'Brandon Grotesque Medium', sans-serif; }
.font-brandon-grotesque-light { font-family: 'Brandon Grotesque Light', sans-serif; }
</style> 