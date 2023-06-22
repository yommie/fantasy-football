<script setup>
import { mapValues } from "lodash"
import { watch, reactive } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { InertiaLink } from "@inertiajs/inertia-vue3"

import pickBy from "lodash/pickBy"
import throttle from "lodash/throttle"
import Icon from "../../components/shared/Icon.vue"
import Pagination from "../../components/shared/Pagination.vue"
import SearchFilter from "../../components/shared/SearchFilter.vue"
import AuthenticatedLayout from "../../layouts/AuthenticatedLayout.vue"

const props = defineProps({
  teams: Object,
  filters: Object
})

const form = reactive({
  search: props.filters.search
})

watch(form, throttle(() => {
  const query = pickBy(form)
  Inertia.replace(
    route(
      'app_teams',
      Object.keys(query).length ? query : { remember: 'forget' }
    )
  )
}))

function reset() {
  form.search = mapValues(form, () => null)
}
</script>

<template>
  <authenticated-layout>
    <h1 class="mb-8 font-bold text-3xl">Teams</h1>

    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset" />

      <inertia-link v-if="!$page.props.userTeam" class="btn-green" :href="route('app_teams_create')">
        <span>Create</span>
        <span class="hidden ml-1 md:inline">Your Team</span>
      </inertia-link>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-2 pt-6 pb-4">Name</th>
          <th class="pt-6 pb-4">Owner</th>
          <th class="pt-6 pb-4" colspan="2">Created</th>
        </tr>

        <tr v-for="team in teams.data" :key="team.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t flex items-center">
            <img class="w-8 h-8 mx-2" :src="team.logo">
            <inertia-link class="pr-6 py-4 flex items-center focus:text-green-500 hover:text-green-500" :href="route('app_teams_view', team.id)">
              {{ team.name }}
            </inertia-link>
          </td>

          <td class="border-t">{{ team.owner.firstName }} {{ team.owner.lastName }}</td>

          <td class="border-t">{{ team.createdAt }}</td>

          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('app_teams_view', team.id)" tabindex="-1">
              <icon name="chevron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>

        <tr v-if="teams.data.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No team found.</td>
        </tr>
      </table>
    </div>

    <pagination :links="teams.links" />
  </authenticated-layout>
</template>
