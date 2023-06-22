<script setup>
import { InertiaLink } from "@inertiajs/inertia-vue3"

const props = defineProps({
  url: String,
})

const teamsLinkExclude = ["app/teams/view"]

function isUrl(url, ...exclude) {
  if (url === '') {
    return props.url === ''
  }

  return props.url.startsWith(url) && exclude.filter(exclude => exclude.startsWith(url)).length === 0
}
</script>

<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('app_teams')">
        <div :class="isUrl('app/teams', ...teamsLinkExclude) ? 'text-white' : 'text-green-300 group-hover:text-white'">Teams</div>
      </inertia-link>
    </div>

    <div class="mb-4" v-if="$page.props.userTeam">
      <inertia-link class="flex items-center group py-3" :href="route('app_teams_view', $page.props.userTeam.id)">
        <div :class="isUrl('app/teams/view') ? 'text-white' : 'text-green-300 group-hover:text-white'">
          {{ $page.props.userTeam.name }}
        </div>
      </inertia-link>
    </div>
  </div>
</template>
