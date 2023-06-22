<script setup>
import {ref, reactive, toRef} from "vue"
import { Inertia } from "@inertiajs/inertia"
import { InertiaLink, usePage } from "@inertiajs/inertia-vue3"

import FileInput from "../../components/shared/form/FileInput.vue"
import TextInput from "../../components/shared/form/TextInput.vue"
import PlayerForm from "../../components/shared/form/PlayerForm.vue"
import LoadingButton from "../../components/shared/LoadingButton.vue"
import AuthenticatedLayout from "../../layouts/AuthenticatedLayout.vue"

defineProps({
  errors: Object
})

const page = usePage()

const playerPositions = page.props.value.playerPositions

const sending = ref(false)

const form = reactive({
  name: null,
  logo: null,
  players: []
})

function addPlayer() {
  form.players.push({
    alias:      null,
    position:   playerPositions[0],
    lastName:   null,
    firstName:  null,
  })
}

function removePlayer(i, player) {
  console.log(i)
  console.log(player)

  if (i > -1) {
    form.players.splice(i, 1)
  }
}

function updatePlayer(i, player) {
  form.players[i] = player
}

function submit() {
  const data = new FormData()

  data.append('name', form.name || '')
  data.append('logo', form.logo || '')
  data.append('players', JSON.stringify(form.players))

  Inertia.post(route('app_teams_create'), data, {
    onStart: () => sending.value = true,
    onFinish: () => sending.value = false,
  })
}
</script>

<script>
export default {
  remember: 'form'
}
</script>

<template>
  <authenticated-layout>
    <div>
      <h1 class="mb-8 font-bold text-3xl">
        <inertia-link class="text-green-400 hover:text-green-600" :href="route('app_teams')">Teams</inertia-link>

        <span class="text-green-400 font-medium"> /</span> Create
      </h1>

      <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
        <form @submit.prevent="submit">
          <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
            <text-input
                label="Name"
                wrapper-class="pr-6 pb-8 w-full lg:w-1/2"
                v-model="form.name"
                :error="errors.name"
            />

            <file-input
                type="file"
                label="Logo"
                accept="image/*"
                class="pr-6 pb-8 w-full lg:w-1/2"
                v-model="form.logo"
                :errors="errors.logo"
            />
          </div>

          <div class="p-8 mb-5">
            <h2 class="mb-5 font-bold text-xl">Players</h2>

            <player-form
                :key="index"
                :player="player"
                v-for="(player, index) in form.players"
                @update-player="updatePlayer(index, $event)"
                @remove-player="removePlayer(index, $event)"
            />

            <button
              type="button"
              class="py-2 flex items-center btn-green"
              @click="addPlayer"
            >
              <div class="text-white">Add Player</div>
            </button>
          </div>

          <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
            <loading-button :loading="sending" class="btn-green" type="submit">Create Team</loading-button>
          </div>
        </form>
      </div>
    </div>
  </authenticated-layout>
</template>
