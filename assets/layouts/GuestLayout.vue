<script setup>
import { ref, reactive } from "vue"
import { Link } from '@inertiajs/inertia-vue3'

import Logo from "../components/shared/Logo.vue"
import TextInput from "../components/shared/form/TextInput.vue"
import LoadingButton from "../components/shared/LoadingButton.vue"

defineProps({
  errors: Object
})

const sending = ref(false)

const form = reactive({
  email: 'johndoe@example.com',
  password: 'secret',
  remember: null,
})

function submit() {
  //
}
</script>

<template>
  <div class="p-6 bg-green-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
      <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />

      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="submit">
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Welcome Back!</h1>

          <div class="mx-auto mt-6 w-24 border-b-2" />

          <text-input v-model="form.email" class="mt-10" label="Email" type="email" autofocus autocapitalize="off" id="email" />
          <text-input v-model="form.password" class="mt-6" label="Password" type="password" id="password"/>
        </div>

        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-end">
          <loading-button :loading="sending" class="btn-green" type="submit">Login</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>