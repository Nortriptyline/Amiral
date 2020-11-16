<template>
  <nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="flex-shrink-0 flex items-center">
            <inertia-link :href="route('dashboard')">
              <jet-application-mark class="block h-9 w-auto" />
            </inertia-link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <jet-nav-link
              :href="route('dashboard')"
              :active="$page.currentRouteName == 'dashboard'"
            >
              Dashboard
            </jet-nav-link>
            <jet-nav-link
              :href="route('clubs')"
              :active="$page.currentRouteName == 'clubs'"
            >
              Clubs
            </jet-nav-link>
          </div>
        </div>

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <div class="ml-3 relative">
            <amiral-notifications-dropdown align="right" width="80">
              <template #trigger>
                <button
                  class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                  <div class="relative">
                    <svg
                      viewBox="0 0 16 16"
                      class="bi bi-bell h-7 w-15"
                      fill="currentColor"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z" />
                      <path
                        fill-rule="evenodd"
                        d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"
                      />
                    </svg>
                    <span
                      v-if="$page.user.unreadNotificationsLength > 0"
                      class="absolute top-0 rounded-full bg-red-500 uppercase w-7 h-7 p-1 text-xs text-white mr-3"
                    >
                      {{ $page.user.unreadNotificationsLength }}
                    </span>
                  </div>
                </button>
              </template>
            </amiral-notifications-dropdown>
          </div>

          <div class="ml-3 relative">
            <jet-dropdown align="right" width="48">
              <template #trigger>
                <button
                  v-if="$page.jetstream.managesProfilePhotos"
                  class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out"
                >
                  <img
                    class="h-8 w-8 rounded-full object-cover"
                    :src="$page.user.profile_photo_url"
                    :alt="$page.user.name"
                  />
                </button>

                <button
                  v-else
                  class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                  <div>{{ $page.user.name }}</div>

                  <div class="ml-1">
                    <svg
                      class="fill-current h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </div>
                </button>
              </template>

              <template #content>
                <!-- Account Management -->
                <amiral-dropdown-title> Manage Account </amiral-dropdown-title>

                <jet-dropdown-link :href="route('profile.show')">
                  Profile
                </jet-dropdown-link>

                <jet-dropdown-link
                  :href="route('api-tokens.index')"
                  v-if="$page.jetstream.hasApiFeatures"
                >
                  API Tokens
                </jet-dropdown-link>

                <amiral-dropdown-title> Manage clubs </amiral-dropdown-title>
                <!-- Clubs Settings -->

                <jet-dropdown-link
                  v-if="$page.user.permissions.editCurrentClub"
                  :href="route('clubs.edit', $page.user.current_club_id)"
                >
                  Club Settings
                </jet-dropdown-link>

                <jet-dropdown-link :href="route('clubs.create')">
                  Create New Club
                </jet-dropdown-link>

                <!-- Clubs Switcher -->
                <div v-if="$page.user.joined_clubs.length > 0">
                  <amiral-dropdown-title> Switch Clubs </amiral-dropdown-title>

                  <template v-for="club in $page.user.joined_clubs">
                    <form @submit.prevent="switchToClub(club)" :key="club.id">
                      <jet-dropdown-link as="button">
                        <div class="flex items-center">
                          <svg
                            v-if="club.id == $page.user.current_club_id"
                            class="mr-2 h-5 w-5 text-green-400"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                          </svg>
                          <div>{{ club.name }}</div>
                        </div>
                      </jet-dropdown-link>
                    </form>
                  </template>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- Authentication -->
                <form @submit.prevent="logout">
                  <jet-dropdown-link as="button"> Logout </jet-dropdown-link>
                </form>
              </template>
            </jet-dropdown>
          </div>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
          >
            <svg
              class="h-6 w-6"
              stroke="currentColor"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div
      :class="{
        block: showingNavigationDropdown,
        hidden: !showingNavigationDropdown,
      }"
      class="sm:hidden"
    >
      <div class="pt-2 pb-3 space-y-1">
        <jet-responsive-nav-link
          :href="route('dashboard')"
          :active="$page.currentRouteName == 'dashboard'"
        >
          Dashboard
        </jet-responsive-nav-link>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="flex items-center px-4">
          <div class="flex-shrink-0">
            <img
              class="h-10 w-10 rounded-full"
              :src="$page.user.profile_photo_url"
              :alt="$page.user.name"
            />
          </div>

          <div class="ml-3">
            <div class="font-medium text-base text-gray-800">
              {{ $page.user.name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
              {{ $page.user.email }}
            </div>
          </div>
        </div>

        <div class="mt-3 space-y-1">
          <jet-responsive-nav-link
            :href="route('profile.show')"
            :active="$page.currentRouteName == 'profile.show'"
          >
            Profile
          </jet-responsive-nav-link>

          <jet-responsive-nav-link
            :href="route('api-tokens.index')"
            :active="$page.currentRouteName == 'api-tokens.index'"
            v-if="$page.jetstream.hasApiFeatures"
          >
            API Tokens
          </jet-responsive-nav-link>

          <!-- Authentication -->
          <form method="POST" @submit.prevent="logout">
            <jet-responsive-nav-link as="button">
              Logout
            </jet-responsive-nav-link>
          </form>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import JetApplicationLogo from "@/Jetstream/ApplicationLogo";
import JetApplicationMark from "@/Jetstream/ApplicationMark";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";
import JetNavLink from "@/Jetstream/NavLink";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";
import AmiralButton from "@/Amiral/Button";
import AmiralDropdownTitle from "@/Amiral/DropdownTitle";
import AmiralNotificationsDropdown from "@/Amiral/NotificationsDropdown";

export default {
  components: {
    JetApplicationLogo,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    AmiralDropdownTitle,
    AmiralButton,
    AmiralNotificationsDropdown
  },

  data() {
    return {
      showingNavigationDropdown: false,
    };
  },

  methods: {
    switchToClub(club) {
      this.$inertia.put(
        route("current-club.update"),
        {
          club_id: club.id,
        },
        {
          preserveState: false,
        }
      );
    },

    logout() {
      axios.post(route("logout").url()).then((response) => {
        window.location = "/";
      });
    },
  },

  computed: {
    path() {
      return window.location.pathname;
    },
  },
};
</script>

<style>
</style>