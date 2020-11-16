<template>
  <amiral-notification
    ref="notification"
    :lock="lock"
    :notification="notification"
  >
    <template #picture>
      <img
        class="w-12 h-12 rounded-full object-cover"
        :src="$page.user.profile_photo_url"
        :alt="$page.user.name"
      />
    </template>
    <template #content>
      You've been invited to join
      {{ notification.data.club.name }}
    </template>

    <template #actions>
      <div v-if="notification.data.status == 'PENDING'" class="flex">
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
        <span class="text-gray-400 text-xs">
          <span v-if="notification.data.status == 'ACCEPTED'">
            Invitation has been <span class="underline">Accepted</span>
          </span>
          <span v-if="notification.data.status == 'DECLINED'">
            Invitation has been <span class="underline">Declined</span>
          </span>
        </span>
      </div>
    </template>
  </amiral-notification>
</template>

<script>
import AmiralNotification from "@/Amiral/Notification";
import AmiralButton from "@/Amiral/Button";

export default {
  components: {
    AmiralNotification,
    AmiralButton,
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
    lock: function () {
      return this.notification.data.status != "PENDING";
    },
  },
  methods: {
    accept() {
      this.$refs.notification.toggleNotification(
        {
          markAsRead: true,
          datas: {
            status: "ACCEPTED",
          },
        },
        () => {
          this.acceptForm.post(
            route("club-members.join", {
              club: this.notification.data.club.id,
              user: this.$page.user.id,
            })
          );
        }
      );
    },
    decline() {
      this.$refs.notification.toggleNotification(
        {
          markAsRead: true,
          datas: {
            status: "DECLINED",
          },
        },
        () => {
          this.declineForm.post(
            route("club-members.destroy", {
              club: this.notification.data.club.id,
              user: this.$page.user.id,
            })
          );
        }
      );
    },
  },
};
</script>

<style>
</style>