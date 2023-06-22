<script setup>
import { ref, watch, nextTick } from "vue"
import { InertiaLink } from "@inertiajs/inertia-vue3"

import Popper from "popper.js"
import Icon from "./Icon.vue"

const props = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
  boundary: {
    type: String,
    default: 'scrollParent',
  },
  autoClose: {
    type: Boolean,
    default: true,
  }
})

const show = ref(false)
const root = ref(null)
const dropdown = ref(null)

let popper

watch(show, async (newShow) => {
  if (newShow) {
    await nextTick()

    popper = new Popper(root.value, dropdown.value, {
      placement: props.placement,
      modifiers: {
        preventOverflow: {
          boundariesElement: props.boundary
        },
      },
    })
  } else if (popper) {
    setTimeout(() => popper.destroy(), 100)
  }
})

document.addEventListener('keydown', (e) => {
  if (e.keyCode === 27) {
    show.value = false
  }
})
</script>

<template>
  <button ref="root" type="button" @click="show = true">
    <div class="flex items-center cursor-pointer select-none group">
      <div class="text-gray-700 group-hover:text-green-600 focus:text-green-600 mr-1 whitespace-no-wrap">
        <span class="mr-1">{{ $page.props.auth.user.firstName }}</span>
        <span class="hidden md:inline">{{ $page.props.auth.user.lastName }}</span>
      </div>
      <icon class="w-5 h-5 group-hover:fill-green-600 fill-gray-700 focus:fill-green-600" name="chevron-down" />
    </div>

    <Teleport to="#app">
      <div v-if="show">
        <div
            style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: .2"
            @click="show = false"
        />

        <div
            ref="dropdown"
            style="position: absolute; z-index: 99999;" @click.stop="show = !autoClose"
        >
          <div class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
            <inertia-link
                class="block px-6 py-2 hover:bg-green-500 hover:text-white"
                :href="route('logout')"
            >Logout</inertia-link>
          </div>
        </div>
      </div>
    </Teleport>
  </button>
</template>
