<script setup>
import { ref, reactive } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { InertiaLink } from "@inertiajs/inertia-vue3"

import Logo from "../../components/shared/Logo.vue"
import TextInput from "../../components/shared/form/TextInput.vue"
import LoadingButton from "../../components/shared/LoadingButton.vue"
import FlashMessages from "../../components/shared/FlashMessages.vue"

defineProps({
  errors: Object
})

const sending = ref(false)

const form = reactive({
  email:      '',
  password:   '',
  lastName:   '',
  firstName:  ''
})

function submit() {
  Inertia.post("/register", form, {
    onStart: () => sending.value = true,
    onFinish: () => sending.value = false,
  })
}
</script>

<template>
  <div class="p-6 bg-green-800 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md md:max-w-[50%]">
      <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />

      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="submit">
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Create an Account</h1>

          <div class="mx-auto mt-6 w-24 border-b-2" />

          <flash-messages />

          <text-input
              autofocus
              id="first-name"
              label="First Name"
              wrapper-class="mt-6"
              :error="errors?.firstName"
              v-model="form.firstName"
          />

          <text-input
              id="last-name"
              label="Last Name"
              wrapper-class="mt-6"
              :error="errors?.lastName"
              v-model="form.lastName"
          />

          <text-input
              id="email"
              type="email"
              label="Email"
              wrapper-class="mt-6"
              autocapitalize="off"
              :error="errors?.email"
              v-model="form.email"
          />

          <text-input
              id="password"
              type="password"
              label="Password"
              wrapper-class="mt-6"
              :error="errors?.password"
              v-model="form.password"
          />
        </div>

        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
          <p>
            Have an account?
            <inertia-link
                class="text-green-800 hover:text-orange-500"
                href="/login"
            >Login</inertia-link>
          </p>
          <loading-button
              type="submit"
              class="btn-green"
              :loading="sending"
          >Create Account</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>