<script setup>
import { ref } from "vue"
import { v4 } from 'uuid'

defineProps({
  id: {
    type: String,
    default: () => {
      return v4()
    }
  },
  type: {
    type: String,
    default: 'text',
  },
  label: String,
  error: String,
  modelValue: String,
  placeholder: String,
  wrapperClass: String
})

defineEmits(['update:modelValue'])

const input = ref(null)

function focus() {
  input.value.focus()
}

function select() {
  input.value.select()
}
</script>

<script>
export default {
  inheritAttrs: false
}
</script>

<template>
  <div :class="wrapperClass">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>

    <input
        ref="input"
        v-bind="$attrs"
        class="form-input"
        :id="id"
        :class="{ error: error }"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
    >

    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
