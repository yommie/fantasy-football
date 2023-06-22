<script setup>
import { ref, watch } from "vue"

const props = defineProps({
  modelValue: File,
  label: String,
  accept: String,
  errors: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(["update:modelValue"])

const file = ref(null)

watch(
    () => props.modelValue,
    () => {
      if (!props.modelValue) {
        file.value = ''
      }
    }
)

function filesize(size) {
  const i = Math.floor(Math.log(size) / Math.log(1024))

  return (size / Math.pow(1024, i) )
      .toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
}

function browse() {
  file.value.click()
}

function change(e) {
  emit('update:modelValue', e.target.files[0])
}

function remove() {
  emit('update:modelValue', null)
}
</script>

<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>

    <div class="form-input p-0" :class="{ error: errors.length }">
      <input ref="file" type="file" :accept="accept" class="hidden" @change="change">

      <div v-if="!modelValue" class="p-2">
        <button
            type="button"
            class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white"
            @click="browse"
        >
          Browse
        </button>
      </div>

      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1 break-all">{{ modelValue.name }}
          <span class="text-gray-500 text-xs">({{ filesize(modelValue.size) }})</span>
        </div>

        <button
            type="button"
            class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white"
            @click="remove"
        >Remove</button>
      </div>
    </div>

    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>
