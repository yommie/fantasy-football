<script setup>
defineProps({
  position: String
})
</script>

<template>
  <span v-if="position === 'GK'" class="py-1 mr-2 text-center inline-block w-10 text-white bg-green-200">GK</span>
  <span v-else-if="position === 'DEF'" class="py-1 mr-2 text-center inline-block w-10 text-white bg-orange-200">DEF</span>
  <span v-else-if="position === 'MID'" class="py-1 mr-2 text-center inline-block w-10 text-white bg-blue-200">MID</span>
  <span v-else-if="position === 'STR'" class="py-1 mr-2 text-center inline-block w-10 text-white bg-red-200">STR</span>
</template>