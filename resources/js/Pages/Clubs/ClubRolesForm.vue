<template>
  <div>
    <!-- Add club Member -->
    <jet-form-section @submitted="addclubMember">
      <template #title> Add club Member </template>

      <template #description>
        Add a new club member to your club, allowing them to collaborate with
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
            v-model="addclubMemberForm.email"
          />
          <jet-input-error
            :message="addclubMemberForm.error('email')"
            class="mt-2"
          />
        </div>

        <!-- Role -->
        <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
          <jet-label for="roles" value="Role" />
          <jet-input-error
            :message="addclubMemberForm.error('role')"
            class="mt-2"
          />

          <div class="mt-1 border border-gray-200 rounded-lg cursor-pointer">
            <div
              class="px-4 py-3"
              :class="{ 'border-t border-gray-200': i > 0 }"
              @click="addclubMemberForm.role = role.key"
              v-for="(role, i) in availableRoles"
              :key="i"
            >
              <div
                :class="{
                  'opacity-50':
                    addclubMemberForm.role &&
                    addclubMemberForm.role != role.key,
                }"
              >
                <!-- Role Name -->
                <div class="flex items-center">
                  <div
                    class="text-sm text-gray-600"
                    :class="{
                      'font-semibold': addclubMemberForm.role == role.key,
                    }"
                  >
                    {{ role.name }}
                  </div>

                  <svg
                    v-if="addclubMemberForm.role == role.key"
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
          :on="addclubMemberForm.recentlySuccessful"
          class="mr-3"
        >
          Added.
        </jet-action-message>

        <jet-button
          :class="{ 'opacity-25': addclubMemberForm.processing }"
          :disabled="addclubMemberForm.processing"
        >
          Add
        </jet-button>
      </template>
    </jet-form-section>

    <div v-if="users.length > 0">
      <jet-section-border />

      <!-- Manage club Members -->
      <jet-action-section class="mt-10 sm:mt-0">
        <template #title> club Members </template>

        <template #description>
          All of the people that are part of this club.
        </template>

        <!-- club Member List -->
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
                <!-- Manage club Member Role -->
                <button
                  class="ml-2 text-sm text-gray-400 underline"
                  v-if="availableRoles.length"
                  @click="manageRole(user)"
                >
                  {{ displayableRole(user.club_role) }}
                </button>
                <!-- 
                                <div class="ml-2 text-sm text-gray-400" v-else-if="availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </div> -->

                <!-- Leave club -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="confirmLeavingclub"
                  v-if="$page.user.id === user.id"
                >
                  Leave
                </button>

                <!-- Remove club Member -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                  @click="confirmclubMemberRemoval(user)"
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
              @click="updateRoleForm.role = role.key"
              v-for="(role, i) in availableRoles"
              :key="i"
            >
              <div
                :class="{
                  'opacity-50':
                    updateRoleForm.role && updateRoleForm.role != role.key,
                }"
              >
                <!-- Role Name -->
                <div class="flex items-center">
                  <div
                    class="text-sm text-gray-600"
                    :class="{
                      'font-semibold': updateRoleForm.role == role.key,
                    }"
                  >
                    {{ role.name }}
                  </div>

                  <svg
                    v-if="updateRoleForm.role == role.key"
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

    <!-- Leave club Confirmation Modal -->
    <jet-confirmation-modal
      :show="confirmingLeavingclub"
      @close="confirmingLeavingclub = false"
    >
      <template #title> Leave club </template>

      <template #content>
        Are you sure you would like to leave this club?
      </template>

      <template #footer>
        <jet-secondary-button @click.native="confirmingLeavingclub = false">
          Nevermind
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click.native="leaveclub"
          :class="{ 'opacity-25': leaveclubForm.processing }"
          :disabled="leaveclubForm.processing"
        >
          Leave
        </jet-danger-button>
      </template>
    </jet-confirmation-modal>

    <!-- Remove club Member Confirmation Modal -->
    <jet-confirmation-modal
      :show="clubMemberBeingRemoved"
      @close="clubMemberBeingRemoved = null"
    >
      <template #title> Remove club Member </template>

      <template #content>
        Are you sure you would like to remove this person from the club?
      </template>

      <template #footer>
        <jet-secondary-button @click.native="clubMemberBeingRemoved = null">
          Nevermind
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click.native="removeclubMember"
          :class="{ 'opacity-25': removeclubMemberForm.processing }"
          :disabled="removeclubMemberForm.processing"
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

  props: ["club", "users", "availableRoles", "userPermissions"],

  data() {
    return {
      addclubMemberForm: this.$inertia.form(
        {
          email: "",
          role: null,
        },
        {
          bag: "addclubMember",
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

      leaveclubForm: this.$inertia.form(
        {
          //
        },
        {
          bag: "leaveclub",
        }
      ),

      removeclubMemberForm: this.$inertia.form(
        {
          //
        },
        {
          bag: "removeclubMember",
        }
      ),

      currentlyManagingRole: false,
      managingRoleFor: null,
      confirmingLeavingclub: false,
      clubMemberBeingRemoved: null,
    };
  },

  methods: {
    addclubMember() {
      this.addclubMemberForm.post(route("club-members.store", this.club), {
        preserveScroll: true,
      });
    },

    manageRole(clubMember) {
      this.managingRoleFor = clubMember;
      this.updateRoleForm.role = clubMember.club_role;
      this.currentlyManagingRole = true;
    },

    updateRole() {
      this.updateRoleForm
        .put(route("club-members.update", [this.club, this.managingRoleFor]), {
          preserveScroll: true,
        })
        .then(() => {
          this.currentlyManagingRole = false;
        });
    },

    confirmLeavingclub() {
      this.confirmingLeavingclub = true;
    },

    leaveclub() {
      this.leaveclubForm.delete(
        route("club-members.destroy", [this.club, this.$page.user])
      );
    },

    confirmclubMemberRemoval(clubMember) {
      this.clubMemberBeingRemoved = clubMember;
    },

    removeclubMember() {
      this.removeclubMemberForm
        .delete(
          route("club-members.destroy", [
            this.club,
            this.clubMemberBeingRemoved,
          ]),
          {
            preserveScroll: true,
            preserveState: true,
          }
        )
        .then(() => {
          this.clubMemberBeingRemoved = null;
        });
    },

    displayableRole(role) {
      return this.availableRoles.find((r) => r.key == role).name;
    },
  },
};
</script>
