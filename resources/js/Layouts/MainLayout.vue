<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const navLinks = [
  { name: 'Historia', href: '/dashboard' },
  { name: 'Kodeks', href: '/kodeks' },
  { name: 'Regulamin', href: '/regulamin' },
  { name: 'Profil', href: '/profile' },
];

const currentPath = computed(() => usePage().url);
</script>

<template>
  <div class="min-h-screen w-full bg-neutral-950 flex flex-col items-center justify-start relative overflow-x-hidden font-brandon-grotesque-medium">
    <!-- NAVIGATION -->
    <nav class="w-full max-w-screen-lg mx-auto flex flex-col items-center pt-8 z-10">
      <div class="flex flex-row justify-center items-center gap-x-12 gap-y-2 text-base select-none">
        <template v-for="link in navLinks" :key="link.name">
          <Link v-if="!link.disabled" :href="link.href" class="text-white cursor-pointer relative flex flex-col items-center px-2"
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