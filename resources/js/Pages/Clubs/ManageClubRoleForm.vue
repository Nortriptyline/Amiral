<template>
  <jet-action-section class="mt-10 sm:mt-0">
    <template #title> Roles Management </template>

    <template #description>
      Create a new role or delete an existing one.
    </template>

    <template #content>
      <div class="space-y-6">
        <div
          class="flex items-center justify-between"
          v-for="(role, index) in roles"
          :key="role.id"
        >
          <div class="flex items-center">
            <div class="ml-4">{{ role.name }}</div>
          </div>

          <div class="flex items-center"></div>

          <div class="flex items-center">
            <button
              class="ml-2 text-sm text-gray-400 underline"
              @click="showRoleUpdateForm(role, index)"
            >
              Edit
            </button>
            <!-- Update Club Role -->
            <button
              v-if="role.slug !== 'admin'"
              class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
              @click="confirmRoleDeletion(index)"
            >
              Remove
            </button>

            <!-- Delete Role Confirmation Modal -->
            <jet-dialog-modal
              :show="confirmingRoleDeletion"
              @close="confirmingRoleDeletion = false"
            >
              <template #title> Delete Role </template>

              <template #content>
                Are you sure you want to delete role {{ role.name }} ? Once this
                role is deleted, you will have to attribute new roles to users
                using this one. Please enter your password to confirm you would
                like to permanently delete your accout.
                <div class="mt-4">
                  <jet-input
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    ref="password"
                    v-model="form.password"
                    @keyup.enter.native="deleteRole(role)"
                  />

                  <jet-input-error
                    :message="form.error('password')"
                    class="mt-2"
                  />
                </div>
              </template>

              <template #footer>
                <jet-secondary-button
                  @click.native="confirmingRoleDeletion = false"
                >
                  Nevermind
                </jet-secondary-button>

                <jet-danger-button
                  class="ml-2"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  @click.native="deleteRole(role)"
                >
                  Delete Role
                </jet-danger-button>
              </template>
            </jet-dialog-modal>

            <!-- Edit Role Name and slug -->
            <jet-dialog-modal
              :show="showRoleUpdate"
              @close="showRoleUpdate = false"
            >
              <template #title> Edit Role : {{ updateRoleForm.name }}</template>

              <template #content>
                <!-- Role name -->
                <div class="col-span-6 sm:col-span-3">
                  <jet-label for="name" value="Role Name" />
                  <jet-input
                    type="text"
                    ref="name"
                    class="mt-1 block w-full"
                    v-model="updateRoleForm.name"
                    autocomplete="name"
                    @keyup.enter.native="updateRole(role)"
                  />
                  <jet-input-error :message="updateRoleForm.error('name')" class="mt-2" />
                </div>
              </template>

              <template #footer>
                <jet-secondary-button @click.native="showRoleUpdate = false">
                  Nevermind
                </jet-secondary-button>

                <jet-button
                  class="ml-2"
                  @click.native="updateRole(role)"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Update Role
                </jet-button>
              </template>
            </jet-dialog-modal>
          </div>
        </div>
      </div>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetActionSection from "@/Jetstream/ActionSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
// import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetDangerButton from "@/Jetstream/DangerButton";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetActionSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetDialogModal,
    JetSecondaryButton,
    JetDangerButton,
  },
  props: ["roles"],

  data() {
    return {
      confirmingRoleDeletion: false,
      showRoleUpdate: false,

      deleting: false,
      form: this.$inertia.form(
        {
          _method: "DELETE",
          password: "",
        },
        {
          bag: "DeleteClubRole",
        }
      ),

      updateRoleForm: this.$inertia.form(
        {
          _method: "PUT",
          name: "",
        },
        {
          bag: "UpdateClubRole",
        }
      ),
    };
  },

  methods: {
    confirmRoleDeletion(roleIndex) {
      this.form.password = "";
      this.confirmingRoleDeletion = true;

      setTimeout(() => {
        this.$refs.password[roleIndex].focus();
      }, 250);
    },

    deleteRole(role) {
      this.form
        .post(route("club-roles.delete", role.id), {
          preserveScroll: true,
        })
        .then((response) => {
          if (!this.form.hasErrors()) {
            this.confirmingRoleDeletion = false;
          }
        });
    },

    showRoleUpdateForm(role, index) {
      this.showRoleUpdate = true;
      this.updateRoleForm.name = role.name;

      setTimeout(() => {
        this.$refs.name[index].focus();
      }, 250);
    },

    updateRole(role) {
      this.updateRoleForm.post(route("club-roles.update", role.id), {
        preserveScroll: true,
      })
      .then((response) => {
        if (!this.updateRoleForm.hasErrors()) {
          this.showRoleUpdate = false;
        }
      })
    },
  },
};
</script>
