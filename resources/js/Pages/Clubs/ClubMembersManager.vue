<template>
  <div>
    <jet-section-border />

    <!-- Add Team Member -->
    <jet-form-section @submitted="addClubMember">
      <template #title> Add club Member </template>

      <template #description>
        Add a new team member to your team, allowing them to collaborate with
        you.
      </template>

      <template #form>
        <div class="col-span-6">
          <div class="max-w-xl text-sm text-gray-600">
            Please provide the email address of the person you would like to add
            to this club. The email address must be associated with an existing
            account.
          </div>
        </div>

        <!-- Member Email -->
        <div class="col-span-6 sm:col-span-4">
          <jet-label for="email" value="Email" />
          <jet-input
            id="email"
            type="text"
            class="mt-1 block w-full"
            v-model="addClubMemberForm.email"
          />
          <jet-input-error
            :message="addClubMemberForm.error('email')"
            class="mt-2"
          />
        </div>

        <!-- Role -->
        <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
          <jet-label for="roles" value="Role" />
          <jet-input-error
            :message="addClubMemberForm.error('role')"
            class="mt-2"
          />

          <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
            <div
              class="px-4 py-3"
              :class="{ 'border-t border-gray-200': i > 0 }"
              @click="addClubMemberForm.role = role.slug"
              v-for="(role, i) in availableRoles"
              :key="i"
            >
              <div
                :class="{
                  'opacity-50':
                    addClubMemberForm.role &&
                    addClubMemberForm.role != role.slug,
                }"
              >
                <!-- Role Name -->
                <div class="flex items-center">
                  <div
                    class="text-sm text-gray-600"
                    :class="{
                      'font-semibold': addClubMemberForm.role == role.slug,
                    }"
                  >
                    {{ role.name }}
                  </div>

                  <svg
                    v-if="addClubMemberForm.role == role.slug"
                    class="ml-2 h-5 w-5 text-green-400"
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
                </div>

                <!-- Role Description -->
                <div class="mt-2 text-xs text-gray-600">
                  {{ role.description }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <template #actions>
        <jet-action-message
          :on="addClubMemberForm.recentlySuccessful"
          class="mr-3"
        >
          Added.
        </jet-action-message>

        <jet-button
          :class="{ 'opacity-25': addClubMemberForm.processing }"
          :disabled="addClubMemberForm.processing"
        >
          Add
        </jet-button>
      </template>
    </jet-form-section>

    <div v-if="users.length > 0">
      <jet-section-border />

      <!-- Manage Team Members -->
      <jet-action-section class="mt-10 sm:mt-0">
        <template #title> Team Members </template>

        <template #description>
          All of the people that are part of this club.
        </template>

        <!-- Team Member List -->
        <template #content>
          <div class="space-y-6">
            <div
              class="flex items-center justify-between"
              v-for="user in users"
              :key="user.id"
            >
              <div class="flex items-center">
                <img
                  class="w-8 h-8 rounded-full"
                  :src="user.profile_photo_url"
                  :alt="user.name"
                />
                <div class="ml-4">{{ user.name }}</div>
              </div>

              <div class="flex items-center">
                <!-- Manage Team Member Role -->
                <button
                  class="ml-2 text-sm text-gray-400 underline"
                  @click="manageRole(user)"
                >
                  {{ roleName(user.pivot.role) }}
                </button>

                <!-- Leave Team -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="confirmLeavingClub"
                  v-if="$page.user.id === user.id"
                >
                  Leave
                </button>

                <!-- Remove Team Member -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="confirmClubMemberRemoval(user)"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </template>
      </jet-action-section>
    </div>

    <!-- Role Management Modal -->
    <jet-dialog-modal
      :show="currentlyManagingRole"
      @close="currentlyManagingRole = false"
    >
      <template #title> Manage Role </template>

      <template #content>
        <div v-if="managingRoleFor">
          <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
            <div
              class="px-4 py-3"
              :class="{ 'border-t border-gray-200': i > 0 }"
              @click="updateRoleForm.role = role.slug"
              v-for="(role, i) in availableRoles"
              :key="i"
            >
              <div
                :class="{
                  'opacity-50':
                    updateRoleForm.role && updateRoleForm.role != role.slug,
                }"
              >
                <!-- Role Name -->
                <div class="flex items-center">
                  <div
                    class="text-sm text-gray-600"
                    :class="{
                      'font-semibold': updateRoleForm.role == role.slug,
                    }"
                  >
                    {{ role.name }}
                  </div>

                  <svg
                    v-if="updateRoleForm.role == role.slug"
                    class="ml-2 h-5 w-5 text-green-400"
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
                </div>

                <!-- Role Description -->
                <!-- <div class="mt-2 text-xs text-gray-600">
                  {{ role.description }}
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </template>

      <template #footer>
        <jet-secondary-button @click.native="currentlyManagingRole = false">
          Nevermind
        </jet-secondary-button>

        <jet-button
          class="ml-2"
          @click.native="updateRole"
          :class="{ 'opacity-25': updateRoleForm.processing }"
          :disabled="updateRoleForm.processing"
        >
          Save
        </jet-button>
      </template>
    </jet-dialog-modal>

    <!-- Leave Team Confirmation Modal -->
    <jet-confirmation-modal
      :show="confirmingLeavingClub"
      @close="confirmingLeavingClub = false"
    >
      <template #title> Leave Team </template>

      <template #content>
        Are you sure you would like to leave this club?
      </template>

      <template #footer>
        <jet-secondary-button @click.native="confirmingLeavingClub = false">
          Nevermind
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click.native="leaveClub"
          :class="{ 'opacity-25': leaveClubForm.processing }"
          :disabled="leaveClubForm.processing"
        >
          Leave
        </jet-danger-button>
      </template>
    </jet-confirmation-modal>

    <!-- Remove Team Member Confirmation Modal -->
    <jet-confirmation-modal
      :show="clubMemberBeingRemoved"
      @close="clubMemberBeingRemoved = null"
    >
      <template #title> Remove Team Member </template>

      <template #content>
        Are you sure you would like to remove this person from the team?
      </template>

      <template #footer>
        <jet-secondary-button @click.native="clubMemberBeingRemoved = null">
          Nevermind
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click.native="removeClubMember"
          :class="{ 'opacity-25': removeClubMemberForm.processing }"
          :disabled="removeClubMemberForm.processing"
        >
          Remove
        </jet-danger-button>
      </template>
    </jet-confirmation-modal>
  </div>
</template>

<script>
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetActionSection from "@/Jetstream/ActionSection";
import JetButton from "@/Jetstream/Button";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetSectionBorder from "@/Jetstream/SectionBorder";

export default {
  components: {
    JetActionMessage,
    JetActionSection,
    JetButton,
    JetConfirmationModal,
    JetDangerButton,
    JetDialogModal,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    JetSectionBorder,
  },

  props: ["club", "users"],

  data() {
    return {
      availableRoles: [
        {
          name: "Administrator",
          slug: "admin",
          description:
            "Administrator is able to manage and edit the club and its users.",
        },
        {
          name: "Editor",
          slug: "editor",
          description:
            "Your editors is only allowed to manage its teams and players. He won't be able to access this page.",
        },
      ],
      addClubMemberForm: this.$inertia.form(
        {
          email: "",
          role: null,
        },
        {
          bag: "addClubMember",
          resetOnSuccess: true,
        }
      ),

      updateRoleForm: this.$inertia.form(
        {
          role: null,
        },
        {
          bag: "updateRole",
          resetOnSuccess: false,
        }
      ),

      leaveClubForm: this.$inertia.form(
        {
          _method: "_DELETE",
        },
        {
          bag: "leaveClub",
        }
      ),

      removeClubMemberForm: this.$inertia.form(
        {
          _method: "_DELETE",
        },
        {
          bag: "removeClubMember",
        }
      ),

      currentlyManagingRole: false,
      managingRoleFor: null,
      confirmingLeavingClub: false,
      clubMemberBeingRemoved: null,
    };
  },

  methods: {
    addClubMember() {
      this.addClubMemberForm.post(route("club-members.store", this.club), {
        preserveScroll: true,
      });
    },

    manageRole(clubMember) {
      this.managingRoleFor = clubMember;
      this.updateRoleForm.role = clubMember.role;
      this.currentlyManagingRole = true;
    },

    updateRole() {
      this.updateRoleForm
        .put(route("team-members.update", [this.team, this.managingRoleFor]), {
          preserveScroll: true,
        })
        .then(() => {
          this.currentlyManagingRole = false;
        });
    },

    confirmLeavingClub() {
      this.confirmingLeavingClub = true;
    },

    leaveClub() {
      this.leaveClubForm.delete(
        route("club-members.destroy", [this.club, this.$page.user]),
        {
          preserveScroll: true,
        }
      );
    },

    confirmClubMemberRemoval(clubMember) {
      this.clubMemberBeingRemoved = clubMember;
    },

    removeClubMember() {
      this.removeClubMemberForm.delete(
        route("club-members.destroy", {
          club: this.club,
          user: this.clubMemberBeingRemoved,
        }),
        {
          preserveScroll: true,
        }
      );
    },

    roleName: function (slug) {
      return this.availableRoles.find((s) => s.slug == slug).name;
    },
  },
};
</script>
