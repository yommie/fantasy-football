<script setup>
defineProps({
  modelValue: String,
  maxWidth: {
    type: Number,
    default: 300,
  },
})

defineEmits(['search:reset', 'update:modelValue'])
</script>

<template>
  <div class="flex items-center">
    <div class="flex w-full bg-white shadow rounded">
      <input
          type="text"
          name="search"
          autocomplete="off"
          placeholder="Search…"
          class="relative w-full px-6 py-3 rounded-r focus:shadow-outline"
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
      >
    </div>

    <button
        type="button"
        class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-green-500"
        @click="$emit('search:reset')"
    >Reset</button>
  </div>
</template>
