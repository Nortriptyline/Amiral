<template>
  <div>
    <div
      class="block flex w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left cursor-pointer hover:bg-indigo-50 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
      :class="background"
      @click="markAsRead"
    >
      <slot name="picture"></slot>
      <div class="pl-5 flex flex-col">
        <div class="py-2 flex">
          <slot name="content"></slot>
          <form @submit.prevent="destroy()">
            <slot name="delete"></slot>
          </form>
        </div>

        <slot name="actions"></slot>
      </div>
    </div>
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
      if (this.notif.read_at === null) {
        axios
          .post("/notification/" + this.notif.id + "/mark-as-read", {
            _method: "PATCH",
          })
          .then((response) => {
            this.notif = response.data.notification;
            if (this.notif.read_at !== null) {
              this.$emit("notif-read");
            }
            return this.notification;
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