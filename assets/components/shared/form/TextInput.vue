<script setup>
import { ref } from "vue"

defineOptions({
  inheritAttrs: false
})

defineProps({
  id: {
    type: String
  },
  type: {
    type: String,
    default: 'text',
  },
  value: String,
  label: String,
  error: String,
})

const input = ref(null)

function focus() {
  input.value.focus()
}

function select() {
  input.value.select()
}
</script>

<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>

    <input
        ref="input"
        v-bind="$attrs"
        class="form-input"
        :id="id"
        :class="{ error: error }"
        :type="type"
        :value="value"
        @input="$emit('input', $event.target.value)"
    >

    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
