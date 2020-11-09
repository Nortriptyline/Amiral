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
              :unreadable="unreadable"
              :color="color"
              v-on:mark-as-read="markAsRead"
              :read="read"
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
  props: ["notification", "unreadable", "action"],
  data: function () {
    return {
      notif: {},
    };
  },
  watch: {
    action: function() {
      if (this.action == "mark-as-read")
      this.markAsRead()
    }
  },
  computed: {
    color: function () {
      return this.read ? "indigo" : "gray";
    },

    read: function () {
      return this.notif.read_at == (null || undefined);
    },

  },
  methods: {
    markAsRead: function () {
      // if (this.notif.read_at === null) {
      axios
        .post("/notification/" + this.notif.id + "/mark-as-read", {
          _method: "PATCH",
        })
        .then((response) => {
          this.notif = response.data.notification;

          this.$emit("notif-read", this.notif.read_at);

          return this.notif;
        });
      // }
    },
  },
  created: function () {
    this.notif = this.notification;
  },
};
</script>

<style>
</style>