<template>
  <div>
    <div
      class="block flex w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left cursor-pointer hover:bg-indigo-50 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
      <slot name="picture"></slot>
      <div class="pl-5 flex flex-col">
        <div class="py-2 flex">
          <slot class="w-3/4" name="content"></slot>
          <div class="w-1/4">
            <amiral-read-button
              v-on:toggle-notification="toggleNotification"
              :lock="lock"
              :notification="notification"
            ></amiral-read-button>
          </div>
        </div>

        <slot name="actions"></slot>
      </div>
    </div>
    <div class="border-t border-gray-200"></div>
  </div>
</template>

<script>
import AmiralReadButton from "@/Amiral/ReadButton";

export default {
  components: {
    AmiralReadButton,
  },
  props: ["notification", "lock"],
  data: function () {
    return {
      toggleReadForm: this.$inertia.form(
        {
          _method: "PATCH",
          markAsRead: !this.read,
          datas: this.notification.data,
        },
        {
          bag: "toggleReadNotification",
        }
      ),
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
    read: function () {
      return this.notification.read_at != null;
    },
  },
  methods: {
    toggleNotification(options, callback) {
      if (!this.lock) {
        if (options != null && options != undefined) {
          this.toggleReadForm.markAsRead = options.markAsRead;
          this.toggleReadForm.datas = Object.assign(
            this.notification.data,
            options.datas
          );
        } else {
          this.toggleReadForm.markAsRead = !this.notification.read_at;

          // testing to delete
          this.toggleReadForm.datas = this.toggleReadForm.datas;
          this.toggleReadForm.datas.status = "PENDING";
        }

        this.toggleReadForm
          .post(
            route("notifications.toggle", {
              notification: this.notification.id,
            })
          )
          .then(() => {
            if (typeof callback === "function") {
              callback();
            }
          });
      }
    },

    deleteNotification() {
      this.deleteForm.post(
        route("notifications.destroy", {
          notification: this.notification.id,
        })
      );
    },
  },
};
</script>

<style>
</style>