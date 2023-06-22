<script setup>
import { ref, watch, nextTick } from "vue"

import Popper from "popper.js"
import SideMenu from "../menus/SideMenu.vue"

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

watch(show, async (newShow, oldShow) => {
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

function url() {
  return location.pathname.substr(1)
}
</script>

<template>
  <button ref="root" type="button" @click="show = true">
    <svg
        class="fill-white w-6 h-6"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
      <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
    </svg>

    <Teleport to="#app">
      <div v-if="show">
        <div
            style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: .2"
            @click="show = false"
        />

        <div
            ref="dropdown"
            style="position: absolute; z-index: 99999;"
            @click.stop="show = !autoClose"
        >
          <div class="mt-2 px-8 py-4 shadow-lg bg-green-800 rounded">
            <side-menu :url="url()" />
          </div>
        </div>
      </div>
    </Teleport>
  </button>
</template>
