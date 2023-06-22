<script setup>
import { ref, reactive } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { InertiaLink } from '@inertiajs/inertia-vue3'

import Logo from "../../components/shared/Logo.vue"
import TextInput from "../../components/shared/form/TextInput.vue"
import LoadingButton from "../../components/shared/LoadingButton.vue"

defineProps({
  errors: Object
})

const sending = ref(false)

const form = reactive({
  email: '',
  password: '',
})

function submit() {
  Inertia.post("/login", form, {
    onStart: () => sending.value = true,
    onFinish: () => sending.value = false,
  })
}
</script>

<template>
  <div class="p-6 bg-green-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
      <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />

      <form
          class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
          @submit.prevent="submit"
      >
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Welcome Back!</h1>

          <div class="mx-auto mt-6 w-24 border-b-2" />

          <text-input
              autofocus
              id="email"
              type="email"
              label="Email"
              autocapitalize="off"
              wrapper-class="mt-10"
              :error="errors.email"
              v-model="form.email"
          />

          <text-input
              id="password"
              type="password"
              label="Password"
              wrapper-class="mt-6"
              v-model="form.password"
          />
        </div>

        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
          <p>
            New here?
            <inertia-link
                class="text-green-800 hover:text-orange-500"
                href="/register"
            >Create an account</inertia-link>
          </p>

          <loading-button
              type="submit"
              class="btn-green"
              :loading="sending"
          >Login</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>