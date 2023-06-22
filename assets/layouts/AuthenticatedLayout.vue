<script setup>
import { ref } from "vue"
import { InertiaLink } from "@inertiajs/inertia-vue3"

import Logo from "../components/shared/Logo.vue"
import SideMenu from "../components/menus/SideMenu.vue"
import FlashMessages from "../components/shared/FlashMessages.vue"
import DropdownUserMenu from "../components/shared/DropdownUserMenu.vue";
import DropdownSideMenu from "../components/shared/DropdownSideMenu.vue";

const showUserMenu = ref(false)

function hideDropdownMenus() {
  showUserMenu.value = false
}

function url() {
  return location.pathname.substr(1)
}
</script>

<template>
  <div class="md:flex md:flex-col">
    <div
        class="md:h-screen md:flex md:flex-col"
        @click="hideDropdownMenus"
    >
      <div class="md:flex md:flex-shrink-0">
        <div class="bg-green-900 md:flex-shrink-0 md:w-56 px-6 py-4 flex items-center justify-between md:justify-center">
          <inertia-link class="mt-1" href="/">
            <logo class="fill-white" width="120" height="28" />
          </inertia-link>

          <dropdown-side-menu class="md:hidden" placement="bottom-end" />
        </div>

        <div class="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-md flex justify-between items-center">
          <div class="mt-1 mr-4">
            <div v-if="$page.props.userTeam" class="flex items-center">
              <img class="w-8 h-8 mx-2" :src="$page.props.userTeam.logo">
              <span class="pr-6 py-4">
                {{ $page.props.userTeam.name }}
              </span>
            </div>
          </div>
          <dropdown-user-menu class="mt-1" placement="bottom-end" />
        </div>
      </div>
      <div class="md:flex md:flex-grow md:overflow-hidden">
        <side-menu :url="url()" class="hidden md:block bg-green-800 flex-shrink-0 w-56 p-12 overflow-y-auto" />
        <div class="md:flex-1 px-4 py-8 md:p-12 md:overflow-y-auto" scroll-region>
          <flash-messages />
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
