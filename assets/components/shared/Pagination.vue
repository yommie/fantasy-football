<script setup>
import { InertiaLink } from "@inertiajs/inertia-vue3"

defineProps({
  links: Array
})
</script>

<template>
  <div class="mt-6 -mb-1 flex flex-wrap">
    <template v-for="(link) in links">
      <div
        class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400"
        v-if="link.url === null"
        :class="{ 'ml-auto': link.label === 'Next' }"
      >{{ link.label }}</div>

      <inertia-link
        v-else
        class="mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-white focus:border-green-500 focus:text-green-500"
        :href="link.url"
        :class="{ 'bg-white': link.active, 'ml-auto': link.label === 'Next' }"
      >{{ link.label }}</inertia-link>
    </template>
  </div>
</template>
