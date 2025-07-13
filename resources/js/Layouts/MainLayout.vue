<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const navLinks = [
  { name: 'Historia', href: '/dashboard' },
  { name: 'Kodeks', href: '/kodeks' },
  { name: 'Regulamin', href: '/regulamin' },
  { name: 'Profil', href: '/profile' },
];

const currentPath = computed(() => usePage().url);
const userPoints = ref(null);
const userName = computed(() => usePage().props.auth?.user?.name || '');
const userAvatar = computed(() => usePage().props.auth?.user?.profile_photo_url || null);
const profileDropdownOpen = ref(false);

function toggleProfileDropdown() {
  profileDropdownOpen.value = !profileDropdownOpen.value;
}

function closeProfileDropdown(e) {
  // zamknij tylko jeśli kliknięcie poza menu i poza przyciskiem
  if (!e.target.closest('.profile-dropdown-fg') && !e.target.closest('.profile-profile-btn')) {
    profileDropdownOpen.value = false;
  }
}

onMounted(async () => {
  try {
    const res = await axios.get('/profile/points');
    userPoints.value = res.data.points;
  } catch (e) {
    userPoints.value = null;
  }
});
onMounted(() => {
  document.addEventListener('click', closeProfileDropdown);
});
onBeforeUnmount(() => {
  document.removeEventListener('click', closeProfileDropdown);
});
</script>

<template>
  <div class="min-h-screen w-full bg-neutral-950 flex flex-col items-center justify-start relative overflow-x-hidden font-brandon-grotesque-medium" style="z-index:99999;">
    <!-- NAVIGATION -->
    <nav class="w-full max-w-screen-lg mx-auto flex flex-col items-center pt-8 z-10">
      <div class="flex flex-row justify-center items-center gap-x-12 gap-y-2 text-base select-none">
        <template v-for="link in navLinks" :key="link.name">
          <div v-if="link.name === 'Profil'" class="relative inline-block" tabindex="0">
            <button type="button" class="profile-profile-btn text-white cursor-pointer relative flex flex-col items-center px-2 focus:outline-none"
              :class="{ 'font-bold': currentPath === link.href }"
              @click.stop="toggleProfileDropdown">
              {{ link.name }}
              <span v-if="currentPath === link.href" class="block w-6 h-0.5 mt-1 bg-white rounded-full" />
              <span v-else class="block w-6 h-0.5 mt-1 bg-white/60 rounded-full opacity-0" />
            </button>
            <!-- Dropdown -->
            <div v-if="profileDropdownOpen" class="profile-dropdown-fg absolute left-1/2 -translate-x-1/2 mt-2 min-w-[340px] bg-[dimgray] rounded-b-2xl py-6 pl-5 pr-[26px] z-[9999] flex flex-row items-center transition-opacity duration-200 shadow-[0_4px_32px_0_rgba(255,255,255,0.10)] font-brandon-grotesque-medium" style="box-shadow:0 4px 32px 0 rgba(255,255,255,0.10);">
              <div class="flex-grow pt-[3px]">
                <div class="flex items-center justify-center gap-[67px] text-white">
                  <div class="flex flex-col items-start gap-1.5">
                    <div class="text-2xl font-medium">
                      Punkty:
                    </div>
                    <div class="font-brandon-grotesque-light">{{ userPoints }} pkt</div>
                  </div>
                  <a href="/profile" class="flex items-center justify-center border-x-[1.2px] border-t-[1.2px] border-solid border-x-[darkgray] border-y-[darkgray] bg-neutral-900 px-[22px] py-2 [border-bottom-width:1.2px] font-brandon-grotesque-light">
                    <div class="flex items-center justify-center gap-2.5">
                      <div>Wgraj pliki</div>
                      <svg class="h-5 w-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <Link v-else-if="!link.disabled" :href="link.href" class="text-white cursor-pointer relative flex flex-col items-center px-2"
            :class="{ 'font-bold': currentPath === link.href }">
            {{ link.name }}
            <span v-if="currentPath === link.href" class="block w-6 h-0.5 mt-1 bg-white rounded-full" />
            <span v-else class="block w-6 h-0.5 mt-1 bg-white/60 rounded-full opacity-0" />
          </Link>
          <span v-else class="text-[dimgray] cursor-default relative flex flex-col items-center px-2">
            {{ link.name }}
            <span class="block w-6 h-0.5 mt-1 bg-white/20 rounded-full opacity-0" />
          </span>
        </template>
      </div>
      <div class="w-full border-b border-white/10 mt-4" />
    </nav>
    <!-- PAGE CONTENT -->
    <main class="w-full flex flex-col items-center flex-1">
      <slot />
    </main>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.animate-fade-in { animation: fade-in 0.3s; }
@keyframes fade-in { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }
.profile-dropdown-fg {
  box-shadow: 0 4px 32px 0 rgba(255,255,255,0.10);
  backdrop-filter: blur(8px);
  z-index: 9999;
}
</style> 