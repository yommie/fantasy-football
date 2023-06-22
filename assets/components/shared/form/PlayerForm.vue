<script setup>
import { toRef } from "vue"
import { usePage } from "@inertiajs/inertia-vue3"

import Icon from "../Icon.vue"
import TextInput from "./TextInput.vue"
import SelectInput from "./SelectInput.vue"

const props = defineProps({
  player: {
    type:     Object,
    required: true
  }
})

const emit = defineEmits(['update-player', 'remove-player'])

const page = usePage()

const player = toRef(props, 'player')

const playerPositions = page.props.value.playerPositions

function updatePlayer(field, value) {
  const p = {...player.value}

  p[field] = value

  emit('update-player', p)
}
</script>

<template>
  <div class="pb-3">
    <div class="pb-3 -mr-6 flex flex-wrap">
      <text-input
          label="First Name"
          wrapper-class="pr-6 w-full lg:w-1/2"
          :model-value="player.firstName"
          @update:modelValue="updatePlayer('firstName', $event)"
      />

      <text-input
          label="Last Name"
          wrapper-class="pr-6 w-full lg:w-1/2"
          :model-value="player.lastName"
          @update:modelValue="updatePlayer('lastName', $event)"
      />
    </div>

    <div class="pb-3 -mr-6 flex flex-wrap">
      <text-input
          label="Alias"
          wrapper-class="pr-6 w-full lg:w-1/2"
          :model-value="player.alias"
          @update:modelValue="updatePlayer('alias', $event)"
      />

      <select-input
          label="Position"
          wrapper-class="pr-6 w-full lg:w-1/2"
          :model-value="player.position"
          :options="playerPositions"
          @update:modelValue="updatePlayer('position', $event)"
      >
        <option
            :key="position"
            :value="position"
            v-for="position in playerPositions"
        >{{ position }}</option>
      </select-input>
    </div>

    <div class="pb-3 -mr-6 flex flex-wrap">
      <button
        type="button"
        class="py-2 flex items-center btn-green"
        @click="emit('remove-player', player)"
      >
        <icon
          name="trash"
          class="w-4 h-4 mr-2 fill-white"
        />

        <div class="text-white">Remove</div>
      </button>
    </div>

    <hr>
  </div>
</template>
