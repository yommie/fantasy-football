<script setup>
import { v4 } from 'uuid'
import { ref, toRefs, watch, computed } from "vue"

const props = defineProps({
  id: {
    type: String,
    default: () => {
      return v4()
    },
  },
  label: String,
  error: String,
  options: Array,
  wrapperClass: String,
  modelValue: [String, Number, Boolean],
})

defineEmits(["update:modelValue"])

const input = ref(null)

const { options, modelValue } = toRefs(props)

const selected = computed(() => {
  const s = options.value.find(o => o === modelValue.value)

  if (typeof s !== 'undefined') {
    return s
  }

  return ''
})

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

    <select
      ref="input"
      class="form-select"
      v-bind="$attrs"
      :id="id"
      :value="selected"
      :class="{ error: error }"
      @input="$emit('update:modelValue', $event.target.value)"
    ><slot /></select>

    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
