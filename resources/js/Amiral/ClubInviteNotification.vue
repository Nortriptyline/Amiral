<template>
  <amiral-notification v-on:notif-read="$emit('mark-as-read')" :notification="notification">
    <template #picture>
      <img
        class="w-12 h-12 rounded-full object-cover"
        :src="$page.user.profile_photo_url"
        :alt="$page.user.name"
      />
    </template>
    <template #content>
      You've been invited to join
      {{ notification.data.club.name }} as a
      {{ notification.data.role.name }}
    </template>

    <template #actions>
      <div v-if="invitationExists" class="flex">
        <form @submit.prevent="accept">
          <amiral-button type="submit" color="indigo">
            Join Club
          </amiral-button>
        </form>
        <form @submit.prevent="decline">
          <amiral-button type="submit" color="red"> Decline </amiral-button>
        </form>
      </div>

      <div class="text-left" v-else>
        <span class="text-gray-400 text-xs"
          >Invitation from {{ notification.data.club.name }} has been
          <span class="underline">{{ status }}</span></span
        >
      </div>
    </template>

    <template #delete>
      <amiral-close-button
        v-if="deletable"
        title="Delete Notification"
        v-tippy
        color="blue"
      ></amiral-close-button>
    </template>
  </amiral-notification>
</template>

<script>
import AmiralNotification from "@/Amiral/Notification";
import AmiralButton from "@/Amiral/Button";
import AmiralCloseButton from "@/Amiral/CloseButton";

export default {
  components: {
    AmiralNotification,
    AmiralButton,
    AmiralCloseButton,
  },
  props: ["notification"],
  data: function () {
    return {
      acceptForm: this.$inertia.form(
        {
          _method: "PATCH",
        },
        {
          bag: "JoinClub",
        }
      ),
      declineForm: this.$inertia.form(
        {
          _method: "DELETE",
        },
        {
          bag: "refuseClubInvitation",
        }
      ),
    };
  },
  computed: {
    invitationExists: function () {
      return this.$page.user.club_invitations.find(
        (c) => c.id == this.notification.data.club.id
      );
    },
    joinedClub: function () {
      return this.$page.user.clubs.find(
        (c) => c.id == this.notification.data.club.id
      );
    },

    deletable: function() {
      return !this.invitationExists
    },

    status: function () {
      return this.joinedClub ? "Accepted" : "Declined";
    },
  },
  methods: {
    accept() {
      this.acceptForm.post(
        route("club-members.join", {
          club: this.notification.data.club.id,
          user: this.$page.user.id,
        })
      );
    },
    decline() {
      this.declineForm.post(
        route("club-members.delete", {
          club: this.notification.data.club.id,
          user: this.$page.user.id,
        })
      );
    },
  },
};
</script>

<style>
</style>