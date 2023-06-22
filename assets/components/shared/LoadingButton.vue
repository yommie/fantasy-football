<script setup>
defineProps({
  loading: Boolean,
  customClass: {
    type: String,
    default: "flex items-center"
  }
})
</script>

<template>
  <button :disabled="loading" :class="customClass">
    <div v-if="loading" class="btn-spinner mr-2" />
    <slot />
  </button>
</template>
