<script setup>
defineProps({
  status: String
})
</script>

<template>
  <span v-if="status === 'accepted'" class="py-1 mr-2 text-center inline-block w-20 text-white bg-green-200">Accepted</span>
  <span v-else-if="status === 'open'" class="py-1 mr-2 text-center inline-block w-20 text-white bg-blue-200">Open</span>
  <span v-else-if="status === 'rejected'" class="py-1 mr-2 text-center inline-block w-20 text-white bg-red-200">Rejected</span>
</template>