<template>
  <div>
    <inertia-link
      class="block flex flex-col w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left cursor-pointer hover:bg-indigo-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
      :class="background"
      href="/dashboard"
    >
      <div class="flex">
        <slot name="picture"></slot>

        <span class="pl-5">
          <slot name="content"></slot>
        </span>

        <form @submit.prevent="destroy()">
          <amiral-close-button color="blue"></amiral-close-button>
        </form>
      </div>

      <div class="flex justify-evenly">
        <slot name="actions"></slot>
      </div>
    </inertia-link>
    <div class="border-t border-gray-200"></div>
  </div>
</template>

<script>
import AmiralCloseButton from "@/Amiral/CloseButton";

export default {
  components: {
    AmiralCloseButton,
  },
  props: ["notification", "closable"],
  data: function () {
    return {
      notif: {},
      deleteForm: this.$inertia.form(
        {
          _method: "DELETE",
        },
        {
          bag: "deleteNotification",
        }
      ),
    };
  },
  computed: {
    background: function () {
      return this.notif.read_at === null ? "bg-indigo-50" : "";
    },
  },
  methods: {
    markAsRead: function () {
      if (this.notification.read_at === null) {
        axios
          .post("/notification/" + this.notif.id + "/mark-as-read", {
            _method: "PATCH",
          })
          .then((response) => {
            return (this.notif = response);
          });
      }
    },
    destroy: function () {
      this.deleteForm.post(
        route(
          "notification.delete",
          {
            notification: this.notif.id,
          },
          {
            preserveState: true,
            preserveScoll: true,
          }
        )
      );
    },
  },
  created: function () {
    this.notif = this.notification;
  },
};
</script>

<style>
</style>