<script setup>
import { ref, reactive } from "vue"
import { InertiaLink, usePage } from "@inertiajs/inertia-vue3"

import { Inertia } from "@inertiajs/inertia"
import BidStatus from "../../components/shared/BidStatus.vue"
import TextInput from "../../components/shared/form/TextInput.vue"
import PlayerPosition from "../../components/shared/PlayerPosition.vue"
import AuthenticatedLayout from "../../layouts/AuthenticatedLayout.vue"

const props = defineProps({
  team: Object,
  bids: Array
})

const page = usePage()

const bidRequest = reactive({
  teamId: page.props.value.userTeam.id,
  amount: null,
  playerId: null,
  playerTeamId: props.team.id
})

const amount = ref(null)

const amountField = ref(null)

const isUserTeam = ref(props.team.owner.id === page.props.value.auth.user.id)

function cancelBid() {
  amount.value = null
  amountField.value = null
}

function submitBid(playerId)
{
  bidRequest.playerId = playerId
  bidRequest.amount = amount.value

  cancelBid()

  Inertia.post(route("app_create_bid"), bidRequest)
}

function acceptBid(bidId) {
  Inertia.post(route("app_accept_bid", bidId))
}

function rejectBid(bidId) {
  Inertia.post(route("app_reject_bid", bidId))
}
</script>

<template>
  <authenticated-layout>
    <div>
      <h1 class="mb-8 font-bold text-3xl flex items-center">
        <inertia-link class="text-green-400 hover:text-green-600" :href="route('app_teams')">Teams</inertia-link>

        <span class="text-green-400 font-medium mx-1">/</span>

        <span>{{ team.name }}</span>

        <img class="mx-2" :src="team.logo">
      </h1>
    </div>

    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <div class="p-8 mb-5">
        <h2 class="mb-5 font-bold text-xl">Players</h2>

        <div v-for="player in team.players" class="flex items-center justify-between mb-2">
          <div v-if="amountField !== player.id">
            <player-position :position="player.position" />

            <span>{{ player.fullName }}</span>

            <span v-if="player.alias">({{ player.alias }})</span>
          </div>

          <div v-else>
            <text-input
                type="number"
                placeholder="Amount"
                wrapper-class="w-full"
                v-model.number="amount"
            />
          </div>

          <span v-if="!isUserTeam">
            <button
                type="button"
                class="py-1 btn-green"
                @click="amountField = player.id"
                v-if="amountField !== player.id"
            >
              <div class="text-white">Bid</div>
            </button>

            <span v-else>
              <button
                  type="button"
                  class="py-1 mr-1 btn-green"
                  @click="submitBid(player.id)"
              >
                <div class="text-white">Bid</div>
              </button>

              <button
                  type="button"
                  class="py-1 btn-red"
                  @click="cancelBid"
              >
                <div class="text-white">Cancel</div>
              </button>
            </span>
          </span>
        </div>
      </div>
    </div>

    <div class="mt-5 bg-white rounded shadow overflow-hidden max-w-3xl">
      <div class="p-8 mb-5">
        <h2 class="mb-5 font-bold text-xl">Bids</h2>

        <div v-if="bids.length">
          <div v-for="bid in bids" class="flex items-center justify-between mb-2">
            <div>
              <bid-status :status="bid.status" />

              <span>{{ bid.player.fullName }} - {{ bid.team.name }} (${{ bid.amount }})</span>
            </div>

            <div v-if="bid.status === 'open' && isUserTeam">
              <button
                  type="button"
                  class="py-1 mr-1 btn-green"
                  @click="acceptBid(bid.id)"
              >
                <div class="text-white">Accept</div>
              </button>

              <button
                  type="button"
                  class="py-1 btn-red"
                  @click="rejectBid(bid.id)"
              >
                <div class="text-white">Reject</div>
              </button>
            </div>
          </div>
        </div>
        <p v-else>No bids for players on this team yet.</p>
      </div>
    </div>
  </authenticated-layout>
</template>
