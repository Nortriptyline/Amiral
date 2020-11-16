<template>
  <div class="relative">
    <div @click="open = !open">
      <slot name="trigger"></slot>
    </div>

    <!-- Full Screen Dropdown Overlay -->
    <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="open"
        class="absolute z-50 mt-2 rounded-md shadow-lg"
        :class="[widthClass]"
        style="display: none"
      >
        <simplebar
          class="rounded-md shadow-xs max-h-75vh z-50"
          :class="contentClasses"
          data-simplebar-auto-hide="false"
        >
          <transition
            enter-active-class="animate__animated animate__slideInRight animate__faster"
            leave-active-class="animate__animated animate__slideOutRight animate__faster"
          >
            <div
              v-show="sub_open"
              class="absolute z-20 bg-white rounded-md shadow w-full top-13"
            >
              <form @submit.prevent="markAllAsRead">
                <button
                  class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                >
                  Mark all as read
                </button>
              </form>
            </div>
          </transition>

          <amiral-dropdown-title
            class="flex justify-between"
            size="text-xl"
            color="text-gray-800"
          >
            <span>Notifications</span>
            <amiral-dot-button
              v-on:clicked="sub_open = !sub_open"
            ></amiral-dot-button>
          </amiral-dropdown-title>
          <div v-if="$page.user.notifications.length > 0">
            <div
              v-for="notification in $page.user.notifications"
              :key="notification.id"
            >
              <amiral-notification-club :notification="notification" />
            </div>
          </div>
          <div
            v-else
            class="block flex w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left cursor-pointer hover:bg-indigo-50 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
          >
            <span>Aucune notification à afficher</span>
          </div>
        </simplebar>
      </div>
    </transition>
  </div>
</template>

<script>
import AmiralNotificationClub from "@/Amiral/ClubInviteNotification";
import AmiralDropdownTitle from "@/Amiral/DropdownTitle";
import AmiralDotButton from "@/Amiral/DotButton";

export default {
  components: {
    AmiralNotificationClub,
    AmiralDropdownTitle,
    AmiralDotButton,
  },
  props: {
    align: {
      default: "right",
    },
    width: {
      default: "48",
    },
    contentClasses: {
      default: () => ["py-1", "bg-white"],
    },
  },

  data() {
    return {
      open: false,
      sub_open: false,
      readAllForm: this.$inertia.form(
        {
          _method: "PATCH",
        },
        {
          bag: "markAllAsRead",
        }
      ),
    };
  },

  methods: {
    markAllAsRead: function () {
      this.readAllForm.post(route("notifications.read_all"), {
        preserveScroll: true,
      }).then(() => {
          this.sub_open = false;
      });
    },
  },

  created() {
    const closeOnEscape = (e) => {
      if (this.open && e.keyCode === 27) {
        this.open = false;
      }
    };

    this.$once("hook:destroyed", () => {
      document.removeEventListener("keydown", closeOnEscape);
    });

    document.addEventListener("keydown", closeOnEscape);
  },

  computed: {
    widthClass() {
      return "w-" + this.width;
    },
  },
};
</script>
